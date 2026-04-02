<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'iv_auart' => ['required', 'string', 'max:4'],
            'iv_werks' => ['required', 'string', 'max:4'],
            'iv_balance' => ['nullable', 'string', 'max:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'iv_auart.required' => 'Parameter iv_auart wajib diisi.',
            'iv_werks.required' => 'Parameter iv_werks wajib diisi.',
        ];
    }
}