<?php

namespace App\Livewire\Dashboard;

use App\Services\AuthService;
use Livewire\Component;

class Logout extends Component
{
    public function logout(AuthService $authService)
    {
        $authService->logoutWeb();
        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.dashboard.logout');
    }
}
