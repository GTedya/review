<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DadataService
{
    private const token = "1e45a9955cb8d843e18ef0785d14843b9eb5170e";
    private const secret = '5e12f08b7ab18fd70d3b225d745340949e9b0280';
    public function findByInn(string $inn): mixed
    {

        $dadata = new \Dadata\DadataClient(self::token, self::secret);
        return $dadata->findById("party", $inn,1);
    }
}
