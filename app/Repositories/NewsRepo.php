<?php

namespace App\Repositories;

use App\Models\News;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsRepo
{
    public function pagination(): LengthAwarePaginator
    {
        return News::query()->orderBy('created_at', 'desc')->paginate();
    }
}
