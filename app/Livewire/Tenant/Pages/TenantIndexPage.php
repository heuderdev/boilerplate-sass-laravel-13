<?php

namespace App\Livewire\Tenant\Pages;


use App\Services\TenantService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use SweetAlert2\Laravel\Traits\WithSweetAlert;

class TenantIndexPage extends Component
{
    use WithSweetAlert;
    public $name;

    public function onSubmit(TenantService $tenantService)
    {

        $response = $tenantService->create(Auth::user(), $this->name);
        if ($response->id) {
            $this->swalToastSuccess([
                'title' => 'Sua empresa foi cadastrada com sucesso.',
            ]);
            $this->name = '';
        }
    }

    public function render()
    {
        return view('livewire.tenant.pages.tenant-index-page');
    }
}
