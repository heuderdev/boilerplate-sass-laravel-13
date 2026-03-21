<?php

namespace App\Http\Middleware;

use App\Services\TenantContextService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentTenant
{
    public function __construct(
        protected TenantContextService $tenantContext
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        // Só executa se houver usuário autenticado
        if (!Auth::check()) {
            return $next($request);
        }

        // Tenta resolver tenant (sessão → token → default)
        $tenant = $this->tenantContext->currentTenant();

        // Se não resolveu nenhum tenant, bloqueia
        if (!$tenant) {
            return $this->tenantNotFound($request);
        }

        // Verifica se assinatura está ativa (ignora super-admin)
        if (!$this->tenantContext->isCurrentTenantActive()) {
            return $this->tenantInactive($request);
        }

        return $next($request);
    }

    private function tenantNotFound(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Nenhum tenant encontrado para este usuário.',
            ], 400);
        }

        return redirect()->route('tenant.select');
    }

    private function tenantInactive(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Assinatura inativa. Renove seu plano.',
            ], 403);
        }

        return redirect()->route('billing.inactive');
    }
}
