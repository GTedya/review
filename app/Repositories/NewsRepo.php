<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepo
{
    public function pagination(int $perPage): LengthAwarePaginator
    {
        return News::query()->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function single(string $slug): ?News
    {
        /** @var ?News $news */
        $news = News::query()->where('slug', $slug)->with('files')->first();
        return $news;
    }
}
