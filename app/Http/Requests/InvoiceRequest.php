<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules($id = ''): array
    {
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'payment_type' => 'required|in:cash,credit',
            'invoice_date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'total' => 'required|numeric|min:0',
            'details' => 'required|array|min:1',
            'details.*.product_id' => 'required|integer',
            'details.*.product_name' => 'required|string',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.unit_price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
        ];

        // if ($this->isMethod('POST')) {
        //     $rules['id'] = [
        //         'required',
        //         'string',
        //         'unique:invoices,id',
        //     ];
        // }

        // if (($this->isMethod('PUT') || $this->isMethod('PATCH')) && $id != '') {
        //     unset($rules['id']);
        //     // $clientId = $this->route("client")->id;
        //     $clientId = $id;
        //     $clientId = $rules['email'][] = 'unique:clients,email,' . $clientId;
        // }

        return $rules;
    }
}
