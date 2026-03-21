<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SendInviteRequest;
use App\Models\Invite;
use App\Services\InviteService;
use App\Services\TenantContextService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function __construct(
        protected InviteService        $inviteService,
        protected TenantContextService $tenantContext
    ) {}

    // GET /api/tenant/invites
    public function index(): JsonResponse
    {
        $invites = $this->inviteService->listByTenant(
            $this->tenantContext->currentTenant()
        );

        return response()->json(['invites' => $invites]);
    }

    // POST /api/tenant/invites
    public function store(SendInviteRequest  $request): JsonResponse
    {
        $invite = $this->inviteService->send(
            $this->tenantContext->currentTenant(),
            $request->user(),
            $request->email,
            $request->role,
            $request->type,
        );

        return response()->json([
            'message' => 'Convite enviado com sucesso.',
            'invite'  => $invite,
        ], 201);
    }

    // POST /api/invites/{token}/accept — usuário logado aceita
    public function accept(Request $request, string $token): JsonResponse
    {
        $invite = $this->inviteService->accept($token, $request->user());

        return response()->json([
            'message' => 'Convite aceito com sucesso.',
            'tenant'  => $invite->tenant,
        ]);
    }

    // POST /api/invites/{token}/accept-new — novo usuário aceita
    public function acceptAsNewUser(Request $request, string $token): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = $this->inviteService->acceptAsNewUser($token, $validated);

        return response()->json([
            'message' => 'Cadastro realizado e convite aceito.',
            'user'    => $result['user'],
            'tenant'  => $result['invite']->tenant,
        ], 201);
    }

    // DELETE /api/tenant/invites/{invite}
    public function destroy(Request $request, Invite $invite): JsonResponse
    {
        $this->inviteService->cancel($invite, $request->user());

        return response()->json(['message' => 'Convite cancelado.']);
    }
}
