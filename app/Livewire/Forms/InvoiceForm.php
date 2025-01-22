<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Http\Requests\InvoiceRequest;
use Livewire\Form;
use Monolog\Logger;

class InvoiceForm extends Form {
    public ?int $id = null;
    public ?string $client_id = '';
    public ?string $payment_type = '';
    public ?string $invoice_date = '';
    public ?string $note = '';
    public ?float $total = 0;
    public ?array $details = [];

    public function reset2(): void {
        $this->id = null;
        $this->client_id = '';
        $this->payment_type = '';
        $this->invoice_date = '';
        $this->note = '';
        $this->total = 0;
        $this->details = [];
    }

    protected function rules(): array {
        //        $request = new InvoiceRequest();
        //        logger('request: ', ['request' => $request]);
        //        if ($isEditing) {
        //            $request->setMethod("PATCH");
        //            return $request->rules();
        //        } else {
        //            logger('ME TRANSFORMARON A POST');
        //            $request->setMethod("POST");
        ////            return array_merge($request->rules(), [
        ////                'details' => ['required', 'array', 'min:1'],
        ////                'details.*.product_name' => ['required', 'string'],
        ////                'details.*.quantity' => ['required', 'integer', 'min:1'],
        ////                'details.*.unit_price' => ['required', 'numeric', 'min:0'],
        ////                'details.*.subtotal' => ['required', 'numeric', 'min:0'],
        ////            ]);
        //            return $request->rules();
        //        }
        $baseRules = [
            'client_id' => 'required|exists:clients,id',
            'payment_type' => 'required|in:cash,credit',
            'invoice_date' => [
                'required',
                'date',
                'after_or_equal:1900-01-01',
                'before_or_equal:' . now()->toDateString(),
            ],
            'note' => 'nullable|string|max:255',
            'total' => 'required|numeric|min:0',
            'details' => 'required|array|min:1',
            'details.*.product_id' => 'required|integer',
            'details.*.product_name' => 'required|string',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.unit_price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
        ];

        if ($this->id) {
            $baseRules['id'] = 'required|exists:invoices,id';
        }

        return $baseRules;
    }

    protected function messages(): array {
        return (new InvoiceRequest())->messages();
    }

    public function store(): Invoice {
        //        dd('DATOS FORM', ['data' => $this->all()]);
        $validated = $this->validate();

        try {
            return DB::transaction(function () use ($validated) {
                // Create invoice
                $invoice = Invoice::create([
                    'client_id' => $this->client_id,
                    'payment_type' => $this->payment_type,
                    'invoice_date' => $this->invoice_date,
                    'note' => $this->note,
                    'total' => $this->total,
                ]);

                // Create invoice details
                foreach ($this->details as $detail) {
                    $invoice->details()->create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $detail['product_id'],
                        'product_name' => $detail['product_name'],
                        'quantity' => $detail['quantity'],
                        'unit_price' => $detail['unit_price'],
                        'subtotal' => $detail['subtotal'],
                    ]);
                }

                return $invoice;
            });
        } catch (\Exception $e) {
            logger()->error('Error creating invoice:', [
                'error' => $e->getMessage(),
                'data' => $this->all(),
            ]);
            throw $e;
        }
    }

    public function update(): Invoice {
        //        logger()->info('Details Data:', ['details' => $this->details]);
        //        dd('DATOS FORM', ['data' => $this->all()]);
        $validated = $this->validate();
        $invoice = Invoice::find($this->id);
        try {
            return DB::transaction(function () use ($validated, $invoice) {
                // Create invoice
                $invoice->update([
                    'client_id' => $this->client_id,
                    'payment_type' => $this->payment_type,
                    'invoice_date' => $this->invoice_date,
                    'note' => $this->note,
                    'total' => $this->total,
                ]);

                //empty details
                $invoice->details()->delete();

                // Create invoice details
                foreach ($this->details as $detail) {
                    $invoice->details()->create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $detail['product_id'],
                        'product_name' => $detail['product_name'],
                        'quantity' => $detail['quantity'],
                        'unit_price' => $detail['unit_price'],
                        'subtotal' => $detail['subtotal'],
                    ]);
                }

                $this->reset();

                return $invoice;
            });
        } catch (\Exception $e) {
            logger()->error('Error updating invoice:', [
                'error' => $e->getMessage(),
                'data' => $this->all(),
            ]);
            throw $e;
        }
    }

    public function save(): void {
        logger('Save method triggered');
        session()->put('temp_invoice_data', $this->all());
    }
}
