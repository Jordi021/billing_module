<?php

namespace App\Livewire\Invoices;

use Livewire\Component;

class ActionsFilters extends Component
{
    public $search = '';
    public $status = 'all';
    public $payment_type = 'all';

    public function updated()
    {
        $this->dispatch('filter-updated', [
            'search' => $this->search,
            'status' => $this->status,
            'payment_type' => $this->payment_type,
        ]);
    }

    public function render()
    {
        return view('livewire.invoices.actions-filters');
    }
}
