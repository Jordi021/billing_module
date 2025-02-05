<?php

namespace App\Livewire\Clients;

use Livewire\Component;

class ActionsFilters extends Component {
    public $search = '';
    public $status = 'all';
    public $client_type = 'all';

    public function resetFilters() {
        $this->reset();
        $this->updated();
    }

    public function updated() {
        $this->dispatch('filter-updated', [
            'search' => $this->search,
            'status' => $this->status,
            'client_type' => $this->client_type,
        ]);
    }

    public function render() {
        return view('livewire.clients.actions-filters');
    }
}
