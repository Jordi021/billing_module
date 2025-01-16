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
    public $details = [];
    public $products = [];
    public $quantity = 1;
    public $invoiceDetails = [];
    public $editingIndex = null;
    public $editingQuantity = null;
    public $total = 0;

    #[On("invoice-edit")]
    public function edit($invoice) {
        $this->isEditing = true;
        $this->form->fill($invoice);
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
