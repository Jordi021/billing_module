<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Http\Requests\InvoiceRequest;
use Livewire\Form;
use Monolog\Logger;
use App\Helpers\DateHelper;

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
        $baseRules = [
            'client_id' => 'required|exists:clients,id',
            'payment_type' => 'required|in:Cash,Credit',
            'note' => 'nullable|string|max:255',
            'total' => 'required|numeric|min:0',
            'details' => 'required|array|min:1',
            'details.*.product_id' => 'required|integer',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.unit_price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
            'details.*.vat_amount' => 'required|numeric|min:0',
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
        $this->validate();

        if ($this->id && Invoice::find($this->id)->is_locked) {
            return null;
        }

        try {
            return DB::transaction(function () {
                $this->invoice_date = now()->format('Y-m-d H:i:s');

                $invoice = Invoice::create([
                    'client_id' => $this->client_id,
                    'payment_type' => $this->payment_type,
                    'invoice_date' => $this->invoice_date,
                    'note' => $this->note,
                    'total' => $this->total,
                ]);

                foreach ($this->details as $detail) {
                    $invoice->details()->create([
                        'product_id' => $detail['product_id'],
                        'quantity' => $detail['quantity'],
                        'unit_price' => $detail['unit_price'],
                        'subtotal' => $detail['subtotal'],
                        'vat_amount' => $detail['vat_amount'],
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

    public function update() {
        $this->validate();
        $invoice = Invoice::find($this->id);

        if ($invoice->is_locked) {
            return null;
        }

        try {
            return DB::transaction(function () use ($invoice) {
                $invoice->update([
                    'client_id' => $this->client_id,
                    'payment_type' => $this->payment_type,
                    'note' => $this->note,
                    'total' => $this->total,
                ]);

                $invoice->details()->delete();

                foreach ($this->details as $detail) {
                    $invoice->details()->create([
                        'product_id' => $detail['product_id'],
                        'quantity' => $detail['quantity'],
                        'unit_price' => $detail['unit_price'],
                        'subtotal' => $detail['subtotal'],
                        'vat_amount' => $detail['vat_amount'],
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

    public function setInvoice(Invoice $invoice): void {
        $this->id = $invoice->id;
        $this->client_id = $invoice->client_id;
        $this->payment_type = $invoice->payment_type;
        $this->invoice_date = DateHelper::formatForDisplay(
            $invoice->invoice_date
        );
        $this->note = $invoice->note;
        $this->total = $invoice->total;
        $this->details = $invoice->details
            ->map(function ($detail) {
                return [
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->unit_price,
                    'subtotal' => $detail->subtotal,
                    'vat_amount' => $detail->vat_amount,
                ];
            })
            ->toArray();
    }

    public function fill($attributes) {
        // Convertir la fecha al formato HTML5 para el input
        if (isset($attributes['invoice_date'])) {
            $attributes['invoice_date'] = DateHelper::formatForHtml(
                $attributes['invoice_date']
            );
        }

        return parent::fill($attributes);
    }

    // Método para obtener la fecha formateada según la región
    public function getFormattedDate(): string {
        if ($this->id !== null || !empty($this->invoice_date)) {
            return DateHelper::fromDatabase($this->invoice_date);
        }
        return DateHelper::getCurrentDateTime();
    }
}
