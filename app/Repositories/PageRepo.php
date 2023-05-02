<?php

namespace App\Repositories;

use App\Models\News;
use App\Models\Page;
use Illuminate\Pagination\LengthAwarePaginator;

class PageRepo
{
    public function pageBySlug(string $slug)
    {
        return Page::query()->where('slug', $slug);
    }
}
