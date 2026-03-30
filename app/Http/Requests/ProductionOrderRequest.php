<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductionOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'p_werks' => ['required', 'string', 'max:4'],
            'p_aufnr' => ['nullable', 'string', 'max:12'],
        ];
    }

    public function messages(): array
    {
        return [
            'p_werks.required' => 'Parameter p_werks wajib diisi.',
        ];
    }
}