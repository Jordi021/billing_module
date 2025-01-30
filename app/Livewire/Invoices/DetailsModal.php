<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\Invoice;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;

class DetailsModal extends Component {
    public $details;
    public $productsInfo;

    #[On('details-modal')]
    public function loadDetails($invoiceId) {
        try {
            // 1. Obtener los detalles de la factura
            $invoice = Invoice::with(['details'])->find($invoiceId);
            $invoiceDetails = $invoice->details;

            // 2. Obtener productos de la API
            $response = Http::withoutVerifying()->get(
                'https://seashell-app-9et5v.ondigitalocean.app/api/productos'
            );
            
            $apiProducts = collect($response->json())->keyBy('Product_Id');

            // 3. Enriquecer los detalles con la información de la API
            $this->details = $invoiceDetails->map(function ($detail) use ($apiProducts) {
                $apiProduct = $apiProducts->get($detail->product_id);
                
                $detail->product_name = $apiProduct['Name'] ?? 'Producto no encontrado';
                $detail->product_description = $apiProduct['Description'] ?? '';
                $detail->product_code = $apiProduct['Code'] ?? '';
                $detail->vat_percentage = $apiProduct['Category']['VAT'] ?? 0;
                
                return $detail;
            });

            $this->dispatch('open-modal', 'details-modal');
            
        } catch (\Exception $e) {
            logger()->error('Error loading invoice details:', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoiceId
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
