<?php

namespace App\Livewire\Invoices;

use App\Livewire\Forms\InvoiceForm;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Client;
use Illuminate\Support\Facades\Http;

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
        $this->isEditing = true;
        $this->form->fill($invoice);

        // Asegurarnos de tener los productos cargados
        if (empty($this->products)) {
            $this->fetchProducts();
        }

        // Reset details to ensure it matches the database records
        $this->form->details = collect($details)
            ->map(function ($detail) {
                // Buscar el producto en la lista de productos
                $product = collect($this->products)->firstWhere('id', $detail['product_id']);
                
                return [
                    'product_id' => $detail['product_id'],
                    'product_name' => $product['title'] ?? 'Producto no encontrado', // Nombre del producto desde la API
                    'unit_price' => $detail['unit_price'],
                    'quantity' => $detail['quantity'],
                    'subtotal' => $detail['subtotal'],
                    'vat_amount' => $detail['vat_amount'],
                    'vat_percentage' => $product['vat_percentage'] ?? 0 // VAT desde la API
                ];
            })
            ->toArray();

        $this->dispatch('open-modal', 'invoice-modal');
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
        $this->dispatch('invoice-created/updated');
        $this->closeModal();
    }

    public function closeModal() {
        $this->dispatch('reset-client-select');
        $this->dispatch('clear-validate-client-id');

        $this->form->reset2(); 
        $this->isEditing = false;
        $this->resetValidation();

        $this->dispatch('close');
    }

    public function mount() {
        $this->form = new InvoiceForm($this, 'form'); // Initialize the InvoiceForm
        $this->form->details = []; // Initialize details as an empty array
        $this->total = 0; // Initialize total

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

    public function fetchProducts() {
        try {
            $response = Http::withoutVerifying()->get(
                'https://seashell-app-9et5v.ondigitalocean.app/api/productos'
            );
            
            $this->products = collect($response->json())
                ->map(function ($product) {
                    return [
                        'id' => $product['Product_Id'],
                        'code' => $product['Code'],
                        'title' => $product['Name'],
                        'description' => $product['Description'],
                        'cost' => (float)$product['Cost'],
                        'price' => (float)$product['Price'],
                        'status' => $product['Status'],
                        'stock' => $product['Stock'],
                        'category_id' => $product['Category']['Category_Id'],
                        'category_type' => $product['Category']['Type'],
                        'vat_percentage' => $product['Category']['VAT']
                    ];
                })
                ->values()
                ->toArray();

            logger()->info('Products fetched successfully', [
                'count' => count($this->products)
            ]);
        } catch (\Exception $e) {
            logger()->error('Error fetching products:', [
                'message' => $e->getMessage(),
            ]);
            $this->products = [];
        }
    }

    public function addDetail($productId, $quantity) {
        if (empty($productId)) {
            return;
        }

        $product = collect($this->products)->firstWhere('id', (int) $productId);

        if (!$product) {
            return;
        }

        // IMPORTANTE: Validación de stock y estado comentada temporalmente para pruebas
        // Descomentar estas líneas en producción o cuando haya productos con stock
        /*
        if (!$product['status'] || $product['stock'] < 1) {
            return;
        }
        */

        $subtotal = $product['price'] * $quantity;
        $vatAmount = $subtotal * ($product['vat_percentage'] / 100);  

        if (!is_array($this->form->details)) {
            $this->form->details = [];
        }

        $existingIndex = collect($this->form->details)->search(function ($detail) use ($productId) {
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
                'vat_amount' => $vatAmount  // Ahora sí agregamos el VAT calculado
            ];
        }

        $this->form->total = collect($this->form->details)->sum(function($detail) {
            return $detail['subtotal'] + $detail['vat_amount'];
        });

        $this->resetInputs();
    }

    public function removeDetail($index) {
        unset($this->form->details[$index]);
        $this->form->details = array_values($this->form->details); // Reindex the array

        // Corregir el cálculo del total para incluir VAT
        $this->form->total = collect($this->form->details)->sum(function($detail) {
            return $detail['subtotal'] + $detail['vat_amount'];
        });
    }

    public function updateQuantity($index, $newQuantity) {
        if ($newQuantity < 1) return;
        
        $this->form->details[$index]['quantity'] = $newQuantity;
        $this->form->details[$index]['subtotal'] = 
            $this->form->details[$index]['quantity'] * 
            $this->form->details[$index]['unit_price'];
        $this->form->details[$index]['vat_amount'] = 
            $this->form->details[$index]['subtotal'] * 
            ($this->form->details[$index]['vat_percentage'] / 100);
        
        $this->form->total = collect($this->form->details)->sum(function($detail) {
            return $detail['subtotal'] + $detail['vat_amount'];
        });
    }

    private function resetInputs() {
        $this->selectedProduct = '';
        $this->quantity = 1;
        // Reset TomSelect
        $this->dispatch('resetProduct');
    }

    public function render() {
        return view('livewire.invoices.modal');
    }
}
