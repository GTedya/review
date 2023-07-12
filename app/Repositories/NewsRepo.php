<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepo
{
    public function pagination(?int $perPage): LengthAwarePaginator
    {
        return News::query()->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function single(string $slug): ?News
    {
        return News::query()->where('slug', $slug)->with('files')->first();
    }
}
