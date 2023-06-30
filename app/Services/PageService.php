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
        $split = explode('/', $slug);
        $currentSlug = array_pop($split);

        $page = $this->pageRepo->pageBySlugWithParents($currentSlug);

        if ($page === null) {
            abort(404, 'Данной страницы не существует');
        };

        $slugs = [];

        $parentPage = $page->parentDeep;
        while ($parentPage?->slug ?? null) {
            $slugs[] = $parentPage->slug;
            $parentPage = $parentPage->parentDeep;
        }

        if (array_reverse($slugs) != $split) {
            abort(404, 'Данной страницы не существует');
        };

        return $page;
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
