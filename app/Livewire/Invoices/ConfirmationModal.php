<?php

namespace App\Livewire\Invoices;


use App\Models\Invoice;
use Livewire\Component;
use Livewire\Attributes\On;

class ConfirmationModal extends Component
{
    public $invoice = null;

    #[On('invoice-confirm')]
    public function confirmModal($invoice)
    {
        $this->invoice = $invoice;
        $this->dispatch('open-modal', 'invoice-modal-confirmation');
    }


    public function deleteInvoice()
    {
        if ($this->invoice) {
            Invoice::find($this->invoice['id'])->delete();
            $this->dispatch('invoice-created/updated');
            $this->dispatch('close-modal', 'invoice-modal-confirmation');
        }
    }
    public function render()
    {
        return view('livewire.invoices.confirmation-modal');
    }
}
