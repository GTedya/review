<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterConfirmationCallRequest;
use App\Http\Requests\RegisterConfirmationCheckRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\RegistrationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RegistrationController extends Controller
{
    public function __construct(private readonly RegistrationService $service)
    {
    }

    /**
     * @throws Exception
     */
    public function registration(RegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->service->registration($data);


        return response()->json(['success' => true]);
    }

    /**
     * @throws Exception
     */
    public function confirmationCall(RegisterConfirmationCallRequest $request): JsonResponse
    {
        $phone = $request->validated('phone');
        $this->service->confirmationCall($phone);
        return response()->json(['success' => true]);
    }

    /**
     * @throws ValidationException
     */
    public function confirmationCheck(RegisterConfirmationCheckRequest $request): JsonResponse
    {
        $phone = $request->validated('phone');
        $code = $request->validated('code');

        $this->service->confirmationCheck($phone, $code);
        return response()->json(['success' => true]);
    }
}
