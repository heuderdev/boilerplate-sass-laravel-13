<?php

namespace App\Livewire\Dashboard;

use App\Models\MemberProfile;
use App\Services\TenantBillingService;
use App\Services\TenantContextService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use SweetAlert2\Laravel\Traits\WithSweetAlert;

#[Layout('layouts.app')]
class DashboardPage extends Component
{
    use WithSweetAlert;

    public string $tenantName      = '';
    public string $tenantSlug      = '';
    public bool   $isSubscribed    = false;
    public int    $totalMembers    = 0;
    public int    $activeMembers   = 0;
    public int    $pendingMembers  = 0;
    public int    $inactiveMembers = 0;
    public int    $totalTenants    = 0;
    public string $memberRole      = '';
    public string $memberStatus    = '';
    public string $priceId         = 'price_1TDeXeFY6BloK9fXeKKsrnu1';
    public string $filterStatus    = '';
    public Collection|array $members = [];
    public $tenant;

    public function updatedFilterStatus(): void
    {
        $this->loadMembers();
    }

    public function loadMembers(): void
    {
        $query = MemberProfile::query();

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $this->members = $query->with('user')->orderBy('created_at', 'desc')->get();
    }

    public function startSubscription(TenantBillingService $billing)
    {
        $user = Auth::user();

        if ($user?->hasRole('super-admin')) {
            $this->alert('warning', 'Super-admin não pode assinar.', options: [
                'timer' => 3000
            ]);
            return;
        }

        try {
            $checkout = $billing->checkoutSubscription(
                $this->tenant,
                $this->priceId,
                route('billing.success'),
                route('billing.cancel')
            );
            return redirect()->away($checkout->url);
        } catch (\Exception $e) {
            $this->alert('error', 'Erro ao criar assinatura: ' . $e->getMessage());
            \Log::error('Subscription error: ' . $e->getMessage(), ['tenant' => $this->tenant->id]);
        }
    }

    public function mount(TenantContextService $tenantContext): void
    {
        $user = Auth::user();
        $this->tenant = $tenantContext->currentTenant();

        if (! $this->tenant) {
            $this->redirect(route('tenant.index'), navigate: true);
            return;
        }

        $this->loadMembers();

        $profile = MemberProfile::where('user_id', $user->id)
            ->where('tenant_id', $this->tenant->id)
            ->first();

        // Contagens por tenant atual
        $this->totalMembers    = $this->tenant->members()->count();
        $this->activeMembers   = $this->tenant->members()->where('status', 'ativo')->count();
        $this->pendingMembers  = $this->tenant->members()->where('status', 'pendente')->count();
        $this->inactiveMembers = $this->tenant->members()->where('status', 'inativo')->count();

        $this->tenantName   = $this->tenant->name;
        $this->tenantSlug   = $this->tenant->slug;
        $this->isSubscribed = $this->tenant->isSubscribed();
        $this->totalTenants = $user->memberProfiles()->distinct('tenant_id')->count('tenant_id');
        $this->memberRole   = $profile?->type ?? '—';
        $this->memberStatus = $profile?->status ?? '—';
    }

    public function render(): View
    {
        return view('livewire.dashboard.dashboard-page');
    }
}
