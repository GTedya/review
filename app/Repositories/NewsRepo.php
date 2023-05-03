<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepo
{
    public function pagination(): LengthAwarePaginator
    {
        return News::query()->orderBy('created_at', 'desc')->paginate();
    }

    public function single(string $slug): ?News
    {
        return News::query()->where('slug', $slug)->with('files')->first();
    }
}
