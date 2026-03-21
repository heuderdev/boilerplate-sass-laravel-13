<?php

namespace App\Livewire\Auth;

use App\Services\AuthService;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.auth')]
class Register extends Component
{
    #[Rule(['required', 'string', 'max:255'], message: [
        'required' => 'O nome é obrigatório.',
        'max'      => 'O nome deve ter no máximo 255 caracteres.',
    ])]
    public string $name = '';

    #[Rule(['required', 'email', 'unique:users,email'], message: [
        'required' => 'O e-mail é obrigatório.',
        'email'    => 'Informe um e-mail válido.',
        'unique'   => 'Este e-mail já está cadastrado.',
    ])]
    public string $email = '';

    #[Rule(['required', 'string', 'min:8'], message: [
        'required' => 'A senha é obrigatória.',
        'min'      => 'A senha deve ter no mínimo 8 caracteres.',
    ])]
    public string $password = '';

    #[Rule(['required', 'string', 'same:password'], message: [
        'required' => 'A confirmação de senha é obrigatória.',
        'same'     => 'A confirmação de senha não confere.',
    ])]
    public string $password_confirmation = '';

    public function register(AuthService $authService): void
    {
        $this->validate();

        try {
            $authService->register([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => $this->password,
            ]);

            $this->redirect(route('dashboard'), navigate: true);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
        }
    }

    public function render(): View
    {
        return view('livewire.auth.register');
    }
}
