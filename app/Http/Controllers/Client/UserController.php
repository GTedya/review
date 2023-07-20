<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepo;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UserController extends Controller
{
    private UserRepo $userRepo;
    private UserService $userService;

    public function __construct(UserRepo $userRepo, UserService $userService)
    {
        $this->userRepo = $userRepo;
        $this->userService = $userService;
    }

    public function info(): JsonResponse
    {
        $user = Auth::user()->load('files', 'company');
        return response()->json(['success' => true, 'user' => new UserResource($user)]);
    }

    public function orders(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $orders = $this->userRepo->getOrders($user);
        return response()->json(['success' => true, 'orders' => OrderListResource::collection($orders)->resource]);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function edit(UserEditRequest $request): JsonResponse
    {
        $user = Auth::user()->load('files');
        $this->userService->edit($user, $request->validated());

        return response()->json(['success' => true]);
    }
}
