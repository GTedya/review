<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            [
                'id' => 1,
                'name' => 'Россгосстрах',
                'link' => '/rosgosstrah',
            ],
            [
                'id' => 2,
                'name' => 'Альфа Банк',
                'link' => '/alfabank',
            ],
            [
                'id' => 3,
                'name' => 'Сбербанк',
                'link' => '/sber',
            ],

        ];

        DB::table('partners')->insert($inserts);
    }
}
