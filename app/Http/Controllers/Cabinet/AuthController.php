<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use App\Repositories\UserRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public UserRepo $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function login(AuthRequest $request)
    {
        $req = $request->validated();

        $user = $this->userRepo->getByEmail($request->input('email'));

        if (!Hash::check($req['password'], $user->password) || $user->hasRole('admin')) {
            throw ValidationException::withMessages(['password' => 'Неверный пароль']);
        }

        $token = $user->createToken($request->header('user-agent'))->plainTextToken;
        return response()->json(['token' => $token]);
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var ?User $user */
        $user = $request->user();

        $result = $user->currentAccessToken()->delete() ?? false;

        return response()->json(['success' => $result]);
    }
}
