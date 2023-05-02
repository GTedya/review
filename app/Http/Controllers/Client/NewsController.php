<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Repositories\NewsRepo;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    public NewsRepo $newsRepo;

    public function __construct(NewsRepo $newsRepo)
    {
        $this->newsRepo = $newsRepo;
    }

    public function pagination(): JsonResponse
    {
        return response()->json(['success' => true, 'news' => $this->newsRepo->pagination()]);
    }
}
