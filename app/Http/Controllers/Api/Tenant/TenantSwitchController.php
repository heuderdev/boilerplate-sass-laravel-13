<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantSwitchController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    // POST /api/tenant/switch/{tenant}
    public function __invoke(Request $request, Tenant $tenant): JsonResponse
    {
        $result = $this->authService->switchTenant($tenant);

        return response()->json([
            'message' => 'Tenant alterado com sucesso.',
            'tenant'  => $result['tenant'],
            'token'   => $result['token'],
        ]);
    }
}
