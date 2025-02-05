<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use Livewire\Attributes\On;

class ActionsFilters extends Component {
    public $search = '';
    public $status = 'all';
    public $payment_type = 'all';

    #[On('reset-filters')]
    public function resetFilters() {
        $this->reset();
        $this->updated();
    }

    public function updated() {
        $this->dispatch('filter-updated', [
            'search' => $this->search,
            'status' => $this->status,
            'payment_type' => $this->payment_type,
        ]);
    }

    public function generateReport() {
        $this->dispatch('get-current-filters');
    }

    #[On('current-filters')]
    public function redirectToReport($filters) {
        $queryParams = http_build_query($filters);          
        return redirect()->to("/invoices/pdf?{$queryParams}");
    }

    public function render() {
        return view('livewire.invoices.actions-filters');
    }
}
