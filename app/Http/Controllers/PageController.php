<?php

namespace App\Http\Controllers;

use App\Repositories\PageRepo;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(PageRepo $pageRepo)
    {
        $this->pageRepo = $pageRepo;
    }

    public function getPage($slug)
    {
        return response()->json(['success' => true, 'page' => $this->pageRepo->pageBySlug($slug)]);
    }
}
