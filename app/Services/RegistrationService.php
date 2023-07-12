<?php

namespace App\Services;

use App\Models\Geo;
use App\Repositories\UserRepo;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegistrationService
{


    public function __construct(
        private readonly UserRepo $userRepo,
        private readonly CallService $callService,
        private readonly DadataService $dadataService,
    ) {
    }

    public function registration(array $data): bool
    {
        $inn = $data['inn'];
        $data['password'] = Hash::make($data['password']);
        $dadataInfo = current($this->dadataService->findByInn($inn));


        $type = strstr($dadataInfo['value'], ' ', true);
        if ($type != 'ИП') {
            $type = 'ООО';
        }

        $iso = $dadataInfo['data']['address']['data']['region_iso_code'];

        $companyData = [
            'org_name' => $dadataInfo['value'],
            'inn' => $inn,
            'org_type' => $type,
            'geo_id' => Geo::query()->where('region_code', $iso)->first()->id,
        ];

        DB::beginTransaction();
        $user = $this->userRepo->create($data, $companyData);
        DB::commit();

        return $user;
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function confirmationCall(string $phone): void
    {
        $user = $this->userRepo->getByPhone($phone);

        if (filled($user->phone_verified_at)) {
            throw ValidationException::withMessages(
                ['user' => 'Ваша учетная запись уже подтверждена']
            );
        };

        $user->phone_confirmation_code = random_int(1000, 9999);
        $user->save();

        $this->callService->flashCall($user->phone, $user->phone_confirmation_code);
    }

    /**
     * @throws ValidationException
     */
    public function confirmationCheck(string $phone, string $code): void
    {
        $user = $this->userRepo->getByPhone($phone);

        if (filled($user->phone_verified_at)) {
            throw ValidationException::withMessages(
                ['user' => 'Ваша учетная запись уже подтверждена']
            );
        };

        if ($code != $user->phone_confirmation_code) {
            throw ValidationException::withMessages(
                ['user' => 'Неверный код']
            );
        }

        $user->phone_verified_at = now();
        $user->save();
    }
}
