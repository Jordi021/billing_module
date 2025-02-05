<?php

namespace App\Livewire\Invoices;

use App\Livewire\Forms\InvoiceForm;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Client;
use Illuminate\Support\Facades\Http;
use App\Helpers\DateHelper;
use Carbon\Carbon;

class Modal extends Component {
    public $isEditing = false;
    public InvoiceForm $form;
    public $selectedClient = null;
    public $selectedProduct = null;
    public $clients = [];

    public $products = [];
    public $quantity = 1;
    public $editingIndex = null;
    public $editingQuantity = null;
    public array $details = [];
    public ?float $total = 0;

    #[On('invoice-edit')]
    public function edit($invoice, $details) {
        // Primero abrir el modal para mejor UX
        $this->dispatch('open-modal', 'invoice-modal');

        // Luego realizar las operaciones pesadas
        $this->isEditing = true;

        $this->form->fill($invoice);

        if (empty($this->products)) {
            $this->fetchProducts();
        }

        // Optimizar el mapeo de detalles usando collection una sola vez
        $productsCollection = collect($this->products);
        $this->form->details = collect($details)
            ->map(function ($detail) use ($productsCollection) {
                $product = $productsCollection->firstWhere(
                    'id',
                    $detail['product_id']
                );

                return [
                    'product_id' => $detail['product_id'],
                    'product_name' =>
                        $product['title'] ?? 'Producto no encontrado',
                    'unit_price' => $detail['unit_price'],
                    'quantity' => $detail['quantity'],
                    'subtotal' => $detail['subtotal'],
                    'vat_amount' => $detail['vat_amount'],
                    'vat_percentage' => $product['vat_percentage'] ?? 0,
                    'stock' => $product['stock'] ?? 0,
                ];
            })
            ->toArray();

        $this->form->invoice_date = Carbon::parse($invoice['invoice_date'])
            ->setTimezone(config('app.timezone'))
            ->format('Y-m-d\TH:i:s');

        // Mover este dispatch al final
        $this->dispatch('fill-client-select', $invoice['client_id']);
    }

    #[On('modal-closed')]
    public function handleModalClosed() {
        if ($this->isEditing) {
            $this->isEditing = false;
            $this->form->reset();
            $this->dispatch('reset-client-select');
        }
        $this->selectedClient = null;
        $this->form->resetValidation();
        $this->dispatch('clear-validate-client-id');
    }

    public function save() {
        empty($this->form->client_id)
            ? $this->dispatch(
                'validate-client-id',
                __('The client field is required.')
            )
            : $this->dispatch('clear-validate-client-id');

        $this->isEditing ? $this->form->update() : $this->form->store();
        
        // Refrescar los productos después de guardar
        $this->products = [];
        $this->fetchProducts();
        
        $this->dispatch('invoice-created/updated');
        $this->dispatch('products-updated'); // Nuevo evento
        $this->closeModal();
    }

    public function closeModal() {
        // Resetear variables del componente
        $this->selectedProduct = null;
        $this->quantity = 1;
        $this->form->reset2();
        $this->isEditing = false;
        $this->resetValidation();
        $this->fetchProducts();

        // Disparar eventos de limpieza
        $this->dispatch('reset-client-select');
        $this->dispatch('clear-validate-client-id');
        $this->dispatch('resetProduct');
        $this->dispatch('close');

        // Refrescar los productos al cerrar el modal
        $this->products = [];
        $this->fetchProducts();
    }

    public function mount() {
        $this->form = new InvoiceForm($this, 'form'); // Initialize the InvoiceForm
        $this->form->details = []; // Initialize details as an empty array
        $this->total = 0; // Initialize total

        // Establecer la fecha actual al montar el componente

        // Load all clients for the dropdown
        $this->clients = Client::orderBy('last_name', 'asc')
            ->get()
            ->map(function ($client) {
                return [
                    'value' => $client->id,
                    'text' => $client->name . ' ' . $client->last_name,
                ];
            })
            ->toArray();

        $this->fetchProducts();
    }

    public function fetchProducts($attempts = 3, $delay = 1000) {
        // Si ya tenemos productos y están en caché, no necesitamos recargarlos
        if (!empty($this->products)) {
            return;
        }

        $attempt = 1;

        do {
            try {
                $response = Http::withoutVerifying()
                    ->timeout(5)
                    ->get(
                        'https://seashell-app-9et5v.ondigitalocean.app/api/productos'
                    );

                if ($response->successful()) {
                    $this->products = collect($response->json())
                        ->map(function ($product) {
                            return [
                                'id' => $product['Product_Id'],
                                'code' => $product['Code'],
                                'title' => $product['Name'],
                                'description' => $product['Description'],
                                'cost' => (float) $product['Cost'],
                                'price' => (float) $product['Price'],
                                'status' => $product['Status'],
                                'stock' => $product['Stock'],
                                'category_id' =>
                                    $product['Category']['Category_Id'],
                                'category_type' => $product['Category']['Type'],
                                'vat_percentage' => $product['Category']['VAT'],
                            ];
                        })
                        ->values()
                        ->toArray();
                    return;
                }

                throw new \Exception('API response was not successful');
            } catch (\Exception $e) {
                if ($attempt === $attempts) {
                    $this->products = [];
                    return;
                }
                usleep($delay * 1000);
                $attempt++;
            }
        } while ($attempt <= $attempts);
    }

    private function getAvailableStock($productId) {
        $product = collect($this->products)->firstWhere('id', $productId);
        if (!$product) {
            return 0;
        }

        $totalUsedStock = collect($this->form->details)
            ->where('product_id', $productId)
            ->sum('quantity');

        return $product['stock'] - $totalUsedStock;
    }

    public function addDetail($productId, $quantity) {
        if (empty($productId)) {
            return;
        }

        $product = collect($this->products)->firstWhere('id', (int) $productId);
        if (!$product) {
            return;
        }

        $availableStock = $this->getAvailableStock($productId);
        if ($quantity > $availableStock) {
            // Opcional: Puedes enviar un mensaje de error al frontend
            return;
        }

        $subtotal = $product['price'] * $quantity;
        $vatAmount = $subtotal * ($product['vat_percentage'] / 100);

        if (!is_array($this->form->details)) {
            $this->form->details = [];
        }

        $existingIndex = collect($this->form->details)->search(function (
            $detail
        ) use ($productId) {
            return $detail['product_id'] == $productId;
        });

        if ($existingIndex !== false) {
            $this->form->details[$existingIndex]['quantity'] += $quantity;
            $this->form->details[$existingIndex]['subtotal'] =
                $this->form->details[$existingIndex]['quantity'] *
                $this->form->details[$existingIndex]['unit_price'];
            $this->form->details[$existingIndex]['vat_amount'] =
                $this->form->details[$existingIndex]['subtotal'] *
                ($this->form->details[$existingIndex]['vat_percentage'] / 100);
        } else {
            $this->form->details[] = [
                'product_id' => $product['id'],
                'product_name' => $product['title'],
                'unit_price' => $product['price'],
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'vat_percentage' => $product['vat_percentage'],
                'vat_amount' => $vatAmount,
                'stock' => $product['stock'], // Get stock directly from product
            ];
        }

        $this->form->total = collect($this->form->details)->sum(function (
            $detail
        ) {
            return $detail['subtotal'] + $detail['vat_amount'];
        });

        $this->resetInputs();
        $this->dispatch('stock-updated');
    }

    public function removeDetail($index) {
        unset($this->form->details[$index]);
        $this->form->details = array_values($this->form->details); // Reindex the array

        // Corregir el cálculo del total para incluir VAT
        $this->form->total = collect($this->form->details)->sum(function (
            $detail
        ) {
            return $detail['subtotal'] + $detail['vat_amount'];
        });

        $this->dispatch('stock-updated');
    }

    public function updateQuantity($index, $newQuantity) {
        if ($newQuantity < 1) {
            return;
        }

        $productId = $this->form->details[$index]['product_id'];
        $product = collect($this->products)->firstWhere('id', $productId);

        // Calcular el stock disponible incluyendo la cantidad actual del ítem
        $currentQty = $this->form->details[$index]['quantity'];
        $otherItemsQty = collect($this->form->details)
            ->where('product_id', $productId)
            ->where(function ($item, $idx) use ($index) {
                return $idx !== $index;
            })
            ->sum('quantity');

        $availableStock = $product['stock'] - $otherItemsQty;

        if ($newQuantity > $availableStock) {
            return;
        }

        $this->form->details[$index]['quantity'] = $newQuantity;
        $this->form->details[$index]['subtotal'] =
            $this->form->details[$index]['quantity'] *
            $this->form->details[$index]['unit_price'];
        $this->form->details[$index]['vat_amount'] =
            $this->form->details[$index]['subtotal'] *
            ($this->form->details[$index]['vat_percentage'] / 100);

        $this->form->total = collect($this->form->details)->sum(function (
            $detail
        ) {
            return $detail['subtotal'] + $detail['vat_amount'];
        });

        $this->dispatch('stock-updated');
    }

    public function getProductStock($productId) {
        return $this->getAvailableStock($productId);
    }

    private function resetInputs() {
        $this->selectedProduct = '';
        $this->quantity = 1;
        // Reset TomSelect
        $this->dispatch('resetProduct');
    }

    public function render() {
        if (!$this->isEditing) {
            $this->form->invoice_date = now()
                ->setTimezone(config('app.timezone'))
                ->format('Y-m-d\TH:i:s');
        }
        $this->fetchProducts();
        return view('livewire.invoices.modal');
    }
}
