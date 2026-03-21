<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
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
    public function register(RegisterRequest  $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'Cadastro realizado com sucesso.',
            'user'    => $result['user'],
            'tenant'  => $result['tenant'],
            'token'   => $result['token'],
        ], 201);
    }

    // POST /api/auth/login
    public function login(LoginRequest  $request): JsonResponse
    {
        $result = $this->authService->login(
            email: $request->email,
            password: $request->password,
            remember: $request->boolean('remember'),
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
