<?php

namespace App\Repositories;

use App\Models\Page;

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


}
