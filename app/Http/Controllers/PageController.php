<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Repositories\PageRepo;
use App\Services\PageService;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    public PageRepo $pageRepo;
    public PageService $pageService;

    public function __construct(PageRepo $pageRepo, PageService $pageService)
    {
        $this->pageRepo = $pageRepo;
        $this->pageService = $pageService;
    }

    public function getPage($slug): JsonResponse
    {
        $content = $this->pageService->getBySlug($slug);

        return response()->json(
            ['success' => true, 'page' => PageResource::make($content)]
        );
    }

    public function getMainPage(): JsonResponse
    {
        $content = $this->pageService->getByTemplate('main');

        return response()->json(
            ['success' => true, 'page' => PageResource::make($content)]
        );
    }
}
