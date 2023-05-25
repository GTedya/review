<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Repositories\MenuRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private MenuRepo $menuRepo;

    public function __construct(MenuRepo $menuRepo)
    {
        $this->menuRepo = $menuRepo;
    }

    public function list(): JsonResponse
    {
        $menus = MenuResource::collection($this->menuRepo->list());
        return response()->json(['success' => true, 'menu' => $menus]);
    }
}
