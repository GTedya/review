<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Repositories\PageRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public PageRepo $pageRepo;

    public function __construct(PageRepo $pageRepo)
    {
        $this->pageRepo = $pageRepo;
    }

    public function getPage($slug) : JsonResponse
    {
        $page = PageResource::make($this->pageRepo->pageBySlug($slug));
        return response()->json(['success' => true, 'page' => $page]);
    }
}
