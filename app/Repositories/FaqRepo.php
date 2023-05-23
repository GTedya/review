<?php

namespace App\Repositories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;

class FaqRepo
{
    public function list(?int $per_page): Collection
    {
        return Faq::limit($per_page)->orderByDesc('sort_index')->get();
    }
}
