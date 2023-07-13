<?php

namespace App\Http\Controllers;

use App\Repositories\PageRepo;
use App\Services\SitemapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function __construct(public PageRepo $pageRepo, public SitemapService $sitemapService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $host = $request->getSchemeAndHttpHost();
        $pages = $this->sitemapService->getPages($host);
        return response()
            ->json(['pages' => $pages]);
    }
}
