<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    private UserRepo $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @throws ValidationException
     */
    public function createToken(User $user, string $password, mixed $userAgent): string
    {
        if (!Hash::check($password, $user->password) || $user->hasRole('admin')) {
            throw ValidationException::withMessages(['password' => 'Неверный пароль']);
        }

        return $user->createToken($userAgent)->plainTextToken;
    }

    public function getPermissions(User $user): array
    {
        $permissions = [];
        if ($user->hasRole('client')) {
            $permissions = User::ROLE_PERMISSION['client'];
        }
        if ($user->hasAnyRole(['leasing_manager', 'dealer_manager'])) {
            $permissions = User::ROLE_PERMISSION['manager'];
        }
        return $permissions;
    }

    public function saveDeviceKey(?string $key, User $user): void
    {
        if (blank($key)) {
            return;
        }
        $this->userRepo->saveDeviceKey($key, $user);
    }
}
