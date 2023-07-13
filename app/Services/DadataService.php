<?php

namespace App\Services;

use App\Models\Geo;
use Dadata\DadataClient;
use Illuminate\Validation\ValidationException;

class DadataService
{
    private const token = '1e45a9955cb8d843e18ef0785d14843b9eb5170e';
    private const secret = '5e12f08b7ab18fd70d3b225d745340949e9b0280';
    private DadataClient $dadata;

    public function __construct(){
        $this->dadata = new \Dadata\DadataClient(self::token, self::secret);
    }

    public function findByInn(?string $inn): mixed
    {
        return $this->dadata->findById('party', $inn, 1);
    }

    public function dadataCompanyInfo(?string $inn): array
    {
        try {
            $data = current($this->findByInn($inn));
            $value = $data['value'] ?? null;
            if (blank($value)) {
                return [];
            }

            $type = strstr($value, ' ', true);
            if ($type != 'ИП') {
                $type = 'ООО';
            }

            $iso = $data['data']['address']['data']['region_iso_code'] ?? null;
            /** @var ?Geo $geo */
            $geo = Geo::query()->where('region_code', $iso)->first();

            return [
                'org_name' => $value,
                'inn' => $inn,
                'org_type' => $type,
                'geo_id' => $geo?->id,
            ];
        } catch (\Exception) {
            return [];
        }
    }
}
