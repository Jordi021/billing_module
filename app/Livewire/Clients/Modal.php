<?php

namespace App\Livewire\Clients;

use App\Livewire\Forms\ClientForm;
use Livewire\Component;
use Livewire\Attributes\On;

class Modal extends Component {
    public $isEditing = false;
    public ClientForm $form;

    #[On("client-edit")]
    public function edit($client) {
        $this->isEditing = true;
        $this->form->fill($client);
        $this->dispatch("open-modal", "client-modal");
    }

    #[On("modal-closed")]
    public function handleModalClosed() {
        if ($this->isEditing) {
            $this->isEditing = false;
            $this->form->reset();
        }
        $this->form->resetValidation();
    }

    public function save() {
        $this->isEditing ? $this->form->update() : $this->form->store();
        $this->dispatch("client-created/updated");
        $this->closeModal();
    }

    public function closeModal() {
        $this->reset(["form", "isEditing"]);
        $this->resetValidation();
        $this->dispatch("close");
    }

    public function render() {
        return view("livewire.clients.modal");
    }
}
