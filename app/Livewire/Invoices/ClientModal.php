<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\Client;
use App\Models\Invoice;
use Livewire\Attributes\On;

class ClientModal extends Component
{

    public $client;

    #[On('client-modal')]
    public function loadClient($clientId)
    {

        $this->client = Client::find($clientId);
        $this->dispatch('open-modal', 'client-modal');
    }

    public function closeModal()
    {
        $this->dispatch("close");
    }


    public function render()
    {
        logger('ClientModal rendering with client:', ['client' => $this->client]);
        return view('livewire.invoices.client-modal');
    }
}
