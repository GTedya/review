<?php

namespace App\Repositories;

use App\Models\User;

class UserRepo
{
    public function getByEmail($email): ?User
    {
        return User::where('email', $email)->first();
    }
}
