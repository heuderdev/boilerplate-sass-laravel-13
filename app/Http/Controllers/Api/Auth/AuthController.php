<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    // POST /api/auth/register
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $result = $this->authService->register($validated);

        return response()->json([
            'message' => 'Cadastro realizado com sucesso.',
            'user'    => $result['user'],
            'tenant'  => $result['tenant'],
            'token'   => $result['token'],
        ], 201);
    }

    // POST /api/auth/login
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
            'remember' => 'boolean',
        ]);

        $result = $this->authService->login(
            email: $validated['email'],
            password: $validated['password'],
            remember: $validated['remember'] ?? false,
        );

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'user'    => $result['user'],
            'token'   => $result['token'],
        ]);
    }

    // POST /api/auth/logout
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ]);
    }

    // GET /api/auth/me
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user'   => $request->user()->load('defaultTenant'),
            'tenant' => app(\App\Services\TenantContextService::class)->currentTenant(),
        ]);
    }
}
