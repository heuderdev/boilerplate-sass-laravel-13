<?php

namespace App\Livewire\Dashboard;

use App\Models\MemberProfile;
use App\Services\AuthService;
use App\Services\TenantContextService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DashboardPage extends Component
{
    public string $tenantName      = '';
    public string $tenantSlug      = '';
    public bool   $isSubscribed    = false;
    public int    $totalMembers    = 0;
    public int    $totalTenants    = 0;
    public string $memberRole      = '';
    public string $memberStatus    = '';

    public function mount(TenantContextService $tenantContext): void
    {
        $user   = Auth::user();
        $tenant = $tenantContext->currentTenant();

        if (! $tenant) {
            $this->redirect(route('tenant.index'), navigate: true);
            return;
        }

        $profile = MemberProfile::where('user_id', $user->id)
            ->where('tenant_id', $tenant->id)
            ->first();

        $this->tenantName   = $tenant->name;
        $this->tenantSlug   = $tenant->slug;
        $this->isSubscribed = $tenant->isSubscribed();
        $this->totalMembers = $tenant->members()->where('status', 'ativo')->count();
        $this->totalTenants = $user->memberProfiles()->distinct('tenant_id')->count('tenant_id');
        $this->memberRole   = $profile?->type ?? '—';
        $this->memberStatus = $profile?->status ?? '—';
    }

    public function render(): View
    {
        return view('livewire.dashboard.dashboard-page');
    }
}
