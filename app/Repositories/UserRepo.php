<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepo
{
    public function getByPhone(string $phone): ?User
    {
        /** @var User $user */
        $user = User::query()->where('phone', $phone)->first();

        return $user;
    }

    public function getOrders(?User $user): LengthAwarePaginator
    {
        return $user->orders()->orderBy('created_at', 'desc')->with(['geo', 'user'])->paginate();
    }
}
