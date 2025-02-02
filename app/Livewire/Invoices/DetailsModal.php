<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\Invoice;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;

class DetailsModal extends Component {
    public $details = null;
    public $isLoading;
    public $invoiceId;
    public $detailsCount = 0;  // Añadir esta propiedad

    #[On('details-modal')]
    public function openModal($invoiceId) {
        $this->reset(['details']);
        $this->invoiceId = $invoiceId;
        $this->isLoading = true;
        
        // Obtener la cantidad de detalles antes de cargar los datos
        $this->detailsCount = Invoice::find($invoiceId)->details->count();

        // Primero abrimos el modal (mostrará el skeleton)
        $this->dispatch('open-modal', 'details-modal');
        
        // Luego disparamos el evento para cargar los datos
        $this->dispatch('load-invoice-details');
    }

    #[On('load-invoice-details')]
    public function loadData() {
        try {
            $invoice = Invoice::with(['details'])->find($this->invoiceId);
            $response = Http::withoutVerifying()->get(
                'https://seashell-app-9et5v.ondigitalocean.app/api/productos'
            );
            
            $apiProducts = collect($response->json())->keyBy('Product_Id');
            
            $this->details = $invoice->details->map(function ($detail) use ($apiProducts) {
                $apiProduct = $apiProducts->get($detail->product_id);
                
                return [
                    'product_name' => $apiProduct['Name'] ?? __('Producto no encontrado'),
                    'product_description' => $apiProduct['Description'] ?? '',
                    'unit_price' => $detail->unit_price,
                    'quantity' => $detail->quantity,
                    'subtotal' => $detail->subtotal,
                    'vat_amount' => $detail->vat_amount,
                    'vat_percentage' => $apiProduct['Category']['VAT'] ?? 0
                ];
            });
            $this->isLoading = false;
        } catch (\Exception $e) {
            logger()->error('Error loading invoice details:', [
                'error' => $e->getMessage(),
                'invoice_id' => $this->invoiceId
            ]);
        }
    }

    public function closeModal() {
        $this->dispatch('close');
    }

    public function render() {
        return view('livewire.invoices.details-modal');
    }
}
