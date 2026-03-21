<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class OnceCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'price_id' => ['required', 'string'],
            'quantity' => ['integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'price_id.required' => 'O ID do produto é obrigatório.',
            'quantity.integer'  => 'A quantidade deve ser um número inteiro.',
            'quantity.min'      => 'A quantidade mínima é 1.',
        ];
    }
}
