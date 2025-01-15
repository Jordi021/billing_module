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
            'client_id' => ['required', 'exists:clients,id'],
            'invoice_date' => [
                'required',
                'date',
                'after_or_equal:1900-01-01',
                'before_or_equal:' . now()->toDateString(),
            ],
            'note' => ['nullable', 'string'],
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
