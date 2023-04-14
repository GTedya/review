<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public UserRepo $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function info(Request $request): JsonResponse
    {
        $user = Auth::user()->load('files');
        return response()->json(['success' => true, 'user' => new UserResource($user)]);
    }

    public function orders(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $orders = $this->userRepo->getOrders($user);
        return response()->json(['orders' => $orders]);
    }
}
