<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\Attributes\On;

class ConfirmationModal extends Component {
    public $delete = false;
    public $client = null;

    #[On('client-confirm')]
    public function confirmModal($delete, $client) {
        $this->delete = $delete;
        $this->client = $client;
        $this->dispatch('open-modal', 'client-modal-confirmation');
    }

    public function updateClientStatus() {
        if ($this->client) {
            Client::find($this->client['id'])->update([
                'status' => !$this->client['status'],
            ]);
            $this->dispatch('client-created/updated');
            $this->dispatch('close-modal', 'client-modal-confirmation');
        }
    }

    public function render() {
        return view('livewire.clients.confirmation-modal');
    }
}
