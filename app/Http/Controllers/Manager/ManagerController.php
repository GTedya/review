<?php

namespace App\Http\Controllers\Manager;

use App\Events\OrderOfferCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendOfferRequest;
use App\Models\ManagerOffer;
use App\Models\User;
use App\Repositories\ManagerRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ManagerController extends Controller
{
    public function __construct(public ManagerRepo $managerRepo)
    {
    }

    public function logoAdd(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->addMediaFromRequest('logo')->toMediaCollection('logo');
        return response()->json(['status' => 'success']);
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     * @throws ValidationException
     */
    public function sendOffer(int $orderId, SendOfferRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (blank($this->managerRepo->takeOrder($user, $orderId))) {
            throw ValidationException::withMessages(
                ['order' => 'Некорректные данные заказа']
            );
        }

        $file = $request->file('file');
        DB::beginTransaction();

        /** @var ManagerOffer $offer */
        $offer = $user->offers()->create(['order_id' => $orderId]);
        $offer->addMedia($file)->toMediaCollection('offer_file');

        DB::commit();
        OrderOfferCreated::dispatch($offer);
        return response()->json(['success' => true]);
    }
}
