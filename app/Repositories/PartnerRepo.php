<?php

namespace App\Repositories;

use App\Models\Partner;
use Illuminate\Support\Collection;

class PartnerRepo
{
    public function list(?int $limit): Collection
    {
        return Partner::limit($limit)->get();
    }
}
