<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Livewire\Attributes\On;

class DetailsModal extends Component
{

    public $details;

    #[On('details-modal')]
    public function loadDetails($invoiceId)
    {

        $invoice = Invoice::find($invoiceId);
        $this->details = $invoice->details;
        $this->dispatch('open-modal', 'details-modal');
    }

    public function closeModal()
    {
        $this->dispatch("close");
    }


    public function render()
    {
        return view('livewire.invoices.details-modal');
    }
}
