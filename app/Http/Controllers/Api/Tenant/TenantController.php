<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CreateTenantRequest;
use App\Services\AuthService;
use App\Services\TenantContextService;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function __construct(
        protected TenantService        $tenantService,
        protected TenantContextService $tenantContext,
        protected AuthService $authService
    ) {}

    // GET /api/tenant — Lista todos os tenants do usuário
    public function index(Request $request): JsonResponse
    {
        $tenants = $this->tenantService->listByUser($request->user());

        return response()->json(['tenants' => $tenants]);
    }

    // GET /api/tenant/current — Tenant ativo no momento
    public function current(): JsonResponse
    {
        $tenant  = $this->tenantContext->currentTenant();
        $profile = $this->tenantContext->currentMemberProfile();

        return response()->json([
            'tenant'  => $tenant,
            'profile' => $profile,
        ]);
    }

    // POST /api/tenant — Cria novo tenant
    public function store(CreateTenantRequest  $request): JsonResponse
    {
        $tenant = $this->tenantService->create($request->user(), $request->name);
        $result = $this->authService->switchTenant($tenant);

        return response()->json([
            'message' => 'Tenant criado com sucesso.',
            'tenant'  => $tenant,
            'token'   => $result['token'],
        ], 201);
    }
}
