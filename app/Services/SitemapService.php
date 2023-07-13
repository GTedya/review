<?php

namespace App\Services;

use App\Models\Page;
use App\Repositories\PageRepo;
use Illuminate\Support\Collection;

class SitemapService
{
    public PageRepo $pageRepo;

    public function __construct(PageRepo $pageRepo)
    {
        $this->pageRepo = $pageRepo;
    }

    public function getPages($host): Collection
    {
        return $this->pageRepo->pages()->map(
            function (Page $page) use ($host) {
                return [
                    'loc' => trim("$host/{$page->fullSlug()}", '/'),
                    'lastmod' => $page->updated_at,
                    'priority' => $page->priority / 50,
                ];
            }
        );
    }
}
