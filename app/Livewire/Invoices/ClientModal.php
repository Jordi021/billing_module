<?php

namespace App\Livewire\Invoices;

use Livewire\Component;
use App\Models\Client;
use Livewire\Attributes\On;

class ClientModal extends Component
{
    public $client = null;
    public $isLoading = true;
    public $clientId;

    #[On('client-modal')]
    public function openModal($clientId)
    {
        $this->reset(['client']);
        $this->clientId = $clientId;
        $this->isLoading = true;
        
        // Primero abrimos el modal (mostrará el skeleton)
        $this->dispatch('open-modal', 'client-modal');
        
        // Luego disparamos el evento para cargar los datos
        $this->dispatch('load-client-details');
    }

    #[On('load-client-details')]
    public function loadData()
    {
        // Simulamos un pequeño delay para ver el skeleton
        usleep(100000); // 200ms delay
        
        try {
            $this->client = Client::find($this->clientId);
        } catch (\Exception $e) {
            logger()->error('Error loading client details:', [
                'error' => $e->getMessage(),
                'client_id' => $this->clientId
            ]);
        } finally {
            $this->isLoading = false;
        }
    }

    public function closeModal()
    {
        $this->dispatch('close');
    }

    public function render()
    {
        return view('livewire.invoices.client-modal');
    }
}
