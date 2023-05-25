<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function list(): JsonResponse
    {
        return response()->json(['success' => true, 'menu' => MenuResource::collection()]);
    }
}
