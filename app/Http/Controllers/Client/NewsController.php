<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Repositories\NewsRepo;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    public NewsRepo $newsRepo;
    public NewsService $newsService;

    public function __construct(NewsRepo $newsRepo, NewsService $newsService)
    {
        $this->newsService = $newsService;
        $this->newsRepo = $newsRepo;
    }

    public function pagination(): JsonResponse
    {
        return response()->json(['success' => true, 'news' => NewsResource::collection($this->newsRepo->pagination())->resource]);
    }

    public function single(string $slug): JsonResponse
    {
        $content = $this->newsService->getBySlug($slug);

        return response()->json(
            ['success' => true, 'news' => NewsResource::make($content)]
        );
    }
}
