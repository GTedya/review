<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepo
{
    public function getByEmail($email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getOrders(?User $user): LengthAwarePaginator
    {
        return $user->orders()->orderBy('created_at', 'desc')->with(['geo', 'user'])->paginate();
    }
}
