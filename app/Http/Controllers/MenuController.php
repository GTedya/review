<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Repositories\MenuRepo;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    private MenuRepo $menuRepo;

    public function __construct(MenuRepo $menuRepo)
    {
        $this->menuRepo = $menuRepo;
    }

    public function list(): JsonResponse
    {
        $menu = MenuResource::collection($this->menuRepo->list());
        return response()->json(['success' => true, 'menu' => $menu]);
    }
}
