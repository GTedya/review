<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use App\Repositories\UserRepo;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private UserRepo $userRepo;
    private AuthService $authService;

    public function __construct(UserRepo $userRepo, AuthService $authService)
    {
        $this->userRepo = $userRepo;
        $this->authService = $authService;
    }

    /**
     * @throws ValidationException
     */
    public function login(AuthRequest $request): JsonResponse
    {
        $userAgent = $request->header('user-agent');
        /** @var User $user */
        $user = $this->userRepo->getByPhone($request->input('phone'));

        $token = $this->authService->createToken($user, $request->input('password'), $userAgent);
        $permissions = $this->authService->getPermissions($user);
        return response()->json(['token' => $token, 'permissions' => $permissions]);
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var ?User $user */
        $user = $request->user();

        $result = $user->currentAccessToken()->delete() ?? false;

        return response()->json(['success' => $result]);
    }

    public function getPermissions(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $permissions = $this->authService->getPermissions($user);
        return response()->json(['permissions' => $permissions]);
    }

    public function registration(): JsonResponse
    {
        return response()->json([]);
    }
}
