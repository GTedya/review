<?php

namespace App\Repositories;

use App\Models\Claim;

class ClaimRepo
{
    public function create($data): ?Claim
    {
        return Claim::create($data);
    }
}
