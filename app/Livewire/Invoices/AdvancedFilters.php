<?php

namespace App\Livewire\Invoices;

use App\Models\Client;
use Livewire\Component;
use Livewire\Attributes\On;

class AdvancedFilters extends Component {
    public $client_id = '';
    public $start_date = '';
    public $end_date = '';
    public $status = 'all';
    public $payment_type = 'all';

    public function applyFilters() {
        $this->dispatch('reset-basic-filters');
        $this->updateFilters();
    }

    #[On('reset-advance-filters')]
    public function resetFilters() {
        $this->reset();
        $this->updateFilters();
    }

    public function updateFilters() {
        $this->dispatch('advance-filter-updated', [
            'client_id' => $this->client_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'payment_type' => $this->payment_type,
        ]);
        $this->dispatch('close-modal', 'advance-filters-modal');
    }

    public function render() {
        return view('livewire.invoices.advanced-filters', [
            'clients' => Client::has('invoices')->orderBy('last_name')->get(),
        ]);
    }
}
