<?php

namespace App\Repositories;

use App\Models\News;
use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class PageRepo
{
    public function pageBySlug(string $slug): ?Page
    {
        return Page::query()->where('slug', $slug)->with('files')->first();
    }
}
