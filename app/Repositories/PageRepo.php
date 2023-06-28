<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Support\Collection;

class PageRepo
{
    public function pageBySlug(string $slug): ?Page
    {
        return Page::query()->where('slug', $slug)->first();
    }

    public function pageByTemplate(string $template): ?Page
    {
        return Page::query()->where('template', $template)->first();
    }

    public function pages(): Collection
    {
        return Page::query()->orderByDesc('priority')->get();
    }


}
