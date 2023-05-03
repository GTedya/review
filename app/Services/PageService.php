<?php

namespace App\Services;

use App\Repositories\PageRepo;
use App\Models\Page;

class PageService
{
    public PageRepo $pageRepo;

    public function __construct(PageRepo $pageRepo)
    {
        $this->pageRepo = $pageRepo;
    }

    public function getBySlug(string $slug): ?Page
    {
        $content = $this->pageRepo->pageBySlug($slug);
        if ($content === null) {
            abort(404, 'Данной страницы не существует');
        };
        return $content;
    }
}
