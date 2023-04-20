<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function logoAdd(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->addMediaFromRequest('logo')->toMediaCollection('logo');
        return response()->json(['status' => 'success']);
    }
}
