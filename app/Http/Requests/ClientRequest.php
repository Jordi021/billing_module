<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateCedulaRuc;

class ClientRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules($id = ''): array {
        $rules = [
            'name' => ['required', 'string', 'max:50', 'regex:/^[\pL\s]+$/u'],
            'last_name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\pL\s]+$/u',
            ],
            'birth_date' => [
                'required',
                'date',
                'after_or_equal:1900-01-01',
                'before_or_equal:' . now()->toDateString(),
            ],
            'client_type' => ['required', 'string', 'in:Cash,Credit'],
            'address' => ['required', 'string', 'min:10'],
            'phone' => ['required', 'string', 'numeric', 'digits:10'],
            'email' => ['required', 'email'],
        ];

        if ($this->isMethod('POST')) {
            $rules['id'] = ['required', 'string', new ValidateCedulaRuc()];
            $rules['email'][] = 'unique:clients,email';
            $rules['phone'][] = 'unique:clients,phone';
        }

        if (($this->isMethod('PUT') || $this->isMethod('PATCH')) && $id != '') {
            unset($rules['id']);
            // $clientId = $this->route("client")->id;
            $clientId = $id;
            $clientId = $rules['email'][] = 'unique:clients,email,' . $clientId;
            $clientId = $rules['phone'][] = 'unique:clients,phone,' . $clientId;
        }

        return $rules;
    }
}
