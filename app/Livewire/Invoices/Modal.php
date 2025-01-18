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
    public ?float  $total = 0;

    #[On("invoice-edit")]
    public function edit($invoice, $details) {
        $this->isEditing = true;
        $this->form->fill($invoice);
        // Reset details to ensure it matches the database records
        $this->form->details = collect($details)->map(function ($detail) {
            return [
                'product_id' => $detail['product_id'],
                'product_name' => $detail['product_name'],
                'unit_price' => $detail['unit_price'],
                'quantity' => $detail['quantity'],
                'subtotal' => $detail['subtotal'],
            ];
        })->toArray();

        $this->selectedClient = $invoice['client_id'];

        $this->dispatch("open-modal", "invoice-modal");
    }

    #[On("modal-closed")]
    public function handleModalClosed() {
        if ($this->isEditing) {
            $this->isEditing = false;
            $this->form->reset();
        }
        $this->form->resetValidation();
    }

    public function save() {
        $this->isEditing ? $this->form->update() : $this->form->store();
        $this->dispatch("invoice-created/updated");
        $this->closeModal();
    }

    public function closeModal() {
        $this->reset(["form", "isEditing"]);
        $this->resetValidation();
        $this->dispatch("close");
    }


    public function mount()
    {
        $this->form = new InvoiceForm($this, 'form'); // Initialize the InvoiceForm
        $this->form->details = []; // Initialize details as an empty array
        $this->total = 0; // Initialize total

        // Load all clients for the dropdown
        $this->clients = Client::orderBy('last_name', 'asc')->get()
            ->map(function($client) {
                return [
                    'value' => $client->id,
                    'text' => $client->name.' '.$client->last_name
                ];
            })->toArray();


        $this->fetchProducts();

    }

    public function fetchProducts()
    {
        try {
            $response = Http::withoutVerifying()->get('https://fakestoreapi.com/products');
            // Parse and map the products to include only id, title, and price
            $this->products = collect($response->json())->map(function ($product) {
                return [
                    'id' => $product['id'],
                    'title' => $product['title'],
                    'price' => $product['price'],
                ];
            })->toArray();

            logger()->info('Mapped products:', ['products' => $this->products]);
        } catch (\Exception $e) {
            logger()->error('Error fetching products:', ['message' => $e->getMessage()]);
            $this->products = [];
        }
    }

    public function addDetail($productId, $quantity)
    {
        logger()->info('addDetail called', ['productId' => $productId, 'quantity' => $quantity]);

        if (empty($productId)) {
            logger()->error('Product ID is empty.', ['productId' => $productId]);
            return;
        }

        $product = collect($this->products)->firstWhere('id', $productId);
        if ($product) {
            $subtotal = $product['price'] * $quantity;

            // Check if the product already exists in the details
            $existingIndex = collect($this->form->details)->search(function ($detail) use ($productId) {
                return $detail['product_id'] == $productId;
            });

            if ($existingIndex !== false) {
                // Update the existing product's quantity and subtotal
                $this->form->details[$existingIndex]['quantity'] += $quantity;
                $this->form->details[$existingIndex]['subtotal'] += $subtotal;
            } else {
                // Add the new product to the details array
                $this->form->details[] = [
                    'product_id' => $product['id'],
                    'product_name' => $product['title'],
                    'unit_price' => $product['price'],
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }

            // Update the total
            $this->form->total = collect($this->form->details)->sum('subtotal');

            logger()->info('Product added or updated in details', $this->form->details);
        } else {
            logger()->error('Product not found for ID', ['productId' => $productId]);
        }
    }



    public function removeDetail($index)
    {
        unset($this->form->details[$index]);
        $this->form->details = array_values($this->form->details); // Reindex the array

        $this->form->total = array_sum(array_column($this->form->details, 'subtotal'));
    }



    private function resetInputs()
    {
        $this->selectedProduct = '';
        $this->quantity = 1;
        // Reset TomSelect
        $this->dispatch('resetProduct');
    }


    public function render() {
        return view("livewire.invoices.modal");
    }
}
