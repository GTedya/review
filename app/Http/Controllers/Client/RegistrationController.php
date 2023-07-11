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
        $this->service->registration($request->validated());
        return response()->json(['success' => true]);
    }

    /**
     * @throws Exception
     */
    public function confirmationCall(RegisterConfirmationCallRequest $request): JsonResponse
    {
        $this->service->confirmationCall($request->validated());
        return response()->json(['success' => true]);
    }

    /**
     * @throws ValidationException
     */
    public function confirmationCheck(RegisterConfirmationCheckRequest $request): JsonResponse
    {
        $this->service->confirmationCheck($request->validated());
        return response()->json(['success' => true]);
    }
}
