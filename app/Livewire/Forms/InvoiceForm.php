<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Http\Requests\InvoiceRequest;
use Livewire\Form;
use Monolog\Logger;

class InvoiceForm extends Form
{

    public ?int $id = 0;
    public ?int $client_id = 0;
    public ?string $payment_type = "";
    public ?string $invoice_date = "";
    public ?string $note = "";
    public ?float  $total = 0;
    public ?array $details = [];


    protected function rules($isEditing): array {
        $request = new InvoiceRequest();
        logger('request: ', ['request' => $request]);
        if ($isEditing) {
            $request->setMethod("PATCH");
            return $request->rules();
        } else {
            logger('ME TRANSFORMARON A POST');
            $request->setMethod("POST");
            dd($request);
//            return array_merge($request->rules(), [
//                'details' => ['required', 'array', 'min:1'],
//                'details.*.product_name' => ['required', 'string'],
//                'details.*.quantity' => ['required', 'integer', 'min:1'],
//                'details.*.unit_price' => ['required', 'numeric', 'min:0'],
//                'details.*.subtotal' => ['required', 'numeric', 'min:0'],
//            ]);
            return $request->rules();
        }
    }

    protected function messages(): array {
        return (new InvoiceRequest())->messages();
    }

    public function store(): void {
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
