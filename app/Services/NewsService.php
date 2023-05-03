<?php

namespace App\Services;

use App\Repositories\NewsRepo;

class NewsService
{
    public NewsRepo $newsRepo;

    public function __construct(NewsRepo $newsRepo)
    {
        $this->newsRepo = $newsRepo;
    }

    public function getBySlug(string $slug)
    {
        $content = $this->newsRepo->single($slug);
        if ($content === null) {
            abort(404, 'Данной страницы не существует');
        };
        return $content;
    }
}
