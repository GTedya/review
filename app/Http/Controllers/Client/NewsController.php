<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Repositories\NewsRepo;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    public NewsRepo $newsRepo;

    public function __construct(NewsRepo $newsRepo)
    {
        $this->newsRepo = $newsRepo;
    }

    public function pagination()
    {
        return response()->json(['success' => true, 'news' => $this->newsRepo->pagination()]);
    }

    public function single(string $slug): JsonResponse
    {
        $content = $this->newsRepo->single($slug);
        if ($content === null) {
            abort(404, 'Данной страницы не существует');
        }

        $news = NewsResource::make($content);

        return response()->json(
            ['success' => true, 'news' => $news]
        );
    }
}
