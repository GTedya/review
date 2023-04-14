<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function info(Request $request): JsonResponse
    {
        $user = Auth::user()->load('files');
        return response()->json(['success' => true, 'user' => new UserResource($user)]);
    }
}
