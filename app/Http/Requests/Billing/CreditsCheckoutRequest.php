<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class CreditsCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'O valor é obrigatório.',
            'amount.integer'  => 'O valor deve ser um número inteiro (centavos).',
            'amount.min'      => 'O valor mínimo é R$ 5,00 (500 centavos).',
        ];
    }
}
