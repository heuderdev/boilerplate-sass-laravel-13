<?php

namespace App\Livewire\Auth;

use App\Services\AuthService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public string $email    = '';
    public string $password = '';

    protected array $rules = [
        'email'    => ['required', 'email'],
        'password' => ['required', 'string'],
    ];

    public function login(AuthService $authService): void
    {
        $this->validate();

        try {
            $authService->loginWeb(
                email: $this->email,
                password: $this->password,
            );

            $this->redirect(route('dashboard'), navigate: true);
        } catch (ValidationException $e) {
            $this->addError('email', $e->getMessage());
        }
    }

    #[Layout('layouts::auth')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
