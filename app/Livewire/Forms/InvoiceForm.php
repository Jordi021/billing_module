<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Http\Requests\InvoiceRequest;
use Livewire\Form;

class InvoiceForm extends Form
{

    public ?int $id = 0;  
    public ?int $client_id = 0;  
    public ?string $payment_type = "";  
    public ?string $invoice_date = "";
    public ?string $note = "";


    protected function rules($isEditing): array {
        $request = new InvoiceRequest();
        if ($isEditing) {
            $request->setMethod("PATCH");
            return $request->rules();
        } else {
            $request->setMethod("POST");
            return $request->rules();
        }
    }

    protected function messages(): array {
        return (new InvoiceRequest())->messages();
    }

    public function store(): void {
        logger('LLEGUE A MODAL STORE!');
        $this->validate($this->rules(false));
        logger('DATOS FORM', ['data' => $this->all()]);
   

        try {
            $invoice = Invoice::create($this->all());
            $invoice->total = 0;
        } catch (\Throwable $th) {
            throw $th;
        }
      
        $this->reset();
    }

    public function update(): void {
        $this->validate($this->rules(true));
        $invoice = Invoice::find($this->id);
        $invoice->update($this->all());
        $this->reset();
    }

    public function save(): void {
        logger('Save method triggered'); 
        session()->put("temp_invoice_data", $this->all());
    }
}
