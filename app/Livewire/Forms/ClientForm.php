<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Http\Requests\ClientRequest;
use App\Models\Client;

class ClientForm extends Form {
    public ?string $id = "";
    public ?string $name = "";
    public ?string $last_name = "";
    public ?string $birth_date = "";
    public ?string $client_type = "";  
    public ?string $address = "";
    public ?string $phone = "";
    public ?string $email = "";

    protected function rules($isEditing): array {
        $request = new ClientRequest();
        if ($isEditing) {
            $request->setMethod("PATCH");
            return $request->rules();
        } else {
            $request->setMethod("POST");
            return $request->rules($this->id);
        }
    }

    protected function messages(): array {
        return (new ClientRequest())->messages();
    }

    public function store(): void {
        $this->validate($this->rules(false));
        Client::create($this->all());
        $this->reset();
    }

    public function update(): void {
        $this->validate($this->rules(true));
        $client = Client::find($this->id);
        $client->update($this->all());
        $this->reset();
    }

    public function save(): void {
        session()->put("temp_client_data", $this->all());
    }
}
