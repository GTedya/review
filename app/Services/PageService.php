<?php

namespace App\Services;

use App\Models\Page;
use App\Repositories\PageRepo;

class PageService
{
    public PageRepo $pageRepo;

    public function __construct(PageRepo $pageRepo)
    {
        $this->pageRepo = $pageRepo;
    }

    public function getBySlug(string $slug): Page
    {
        $content = $this->pageRepo->pageBySlug($slug);
        if ($content === null) {
            abort(404, 'Данной страницы не существует');
        };
        return $content;
    }

    public function getByTemplate(string $template): Page
    {
        $content = $this->pageRepo->pageByTemplate($template);
        if ($content === null) {
            abort(404, 'Данной страницы не существует');
        };
        return $content;
    }
}
