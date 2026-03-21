<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendInviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roles = ['owner', 'admin', 'manager', 'member', 'contributor', 'viewer', 'guest'];

        return [
            'email' => ['required', 'email'],
            'role'  => ['required', 'string', Rule::in($roles)],
            'type'  => ['required', 'string', Rule::in($roles)],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email'    => 'Informe um e-mail válido.',
            'role.required'  => 'O papel (role) é obrigatório.',
            'role.in'        => 'O papel informado não é válido.',
            'type.required'  => 'O tipo é obrigatório.',
            'type.in'        => 'O tipo informado não é válido.',
        ];
    }
}
