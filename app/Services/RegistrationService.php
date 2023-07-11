<?php

namespace App\Services;

use App\Http\Requests\RegisterConfirmationCallRequest;
use App\Http\Requests\RegisterConfirmationCheckRequest;
use App\Repositories\UserRepo;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class RegistrationService
{
    private const CALL_BASE_URL = "https://api.unibell.ru/apps/flash/calls/flash";
    private const ACCESS_KEY = "Basic pQv29uazeivPZwAQGo9PHl8tA6H4lkeG";

    public function __construct(private readonly UserRepo $userRepo,)
    {
    }

    /**
     * @throws ValidationException
     */
    public function registration(array $data): bool
    {
        $data['password'] = Hash::make($data['password']);

        DB::beginTransaction();
        $user = $this->userRepo->create($data);
        DB::commit();

        return $user;
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function confirmationCall(RegisterConfirmationCallRequest $request): void
    {
        $user = $this->userRepo->getByPhone($request->validated('phone'));

        if (filled($user->phone_verified_at)) {
            throw ValidationException::withMessages(
                ['user' => 'Ваша учетная запись уже подтверждена']
            );
        };

        $user->phone_confirmation_code = random_int(1000, 9999);
        $user->save();

        Http::withHeaders([
            'Authorization' => self::ACCESS_KEY,
            'Content-Type' => 'application/json',
        ])
            ->post(
                self::CALL_BASE_URL,
                [
                    'number' => $user->phone,
                    'code' => $user->phone_confirmation_code,
                    'timeout' => 10000
                ]
            );
    }

    /**
     * @throws ValidationException
     */
    public function confirmationCheck(RegisterConfirmationCheckRequest $request): void
    {
        $user = $this->userRepo->getByPhone($request->validated('phone'));

        if (filled($user->phone_verified_at)) {
            throw ValidationException::withMessages(
                ['user' => 'Ваша учетная запись уже подтверждена']
            );
        };

        if ($request->input('code') != $user->phone_confirmation_code) {
            throw ValidationException::withMessages(
                ['user' => 'Неверный код']
            );
        }

        $user->phone_verified_at = now();
        $user->save();
    }
}
