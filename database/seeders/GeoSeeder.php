<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            [
                'id' => 1,
                'name' => 'СКФО',
                'region_code' => 'RU-SKFO',
                'parent_id' => null,

            ],
            [
                'id' => 2,
                'name' => 'Респ. Северная Осетия-Алания',
                'region_code' => 'RU-SE',
                'parent_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Москва',
                'region_code' => 'RU-MOS',
                'parent_id' => null,
            ],

        ];

        DB::table('geos')->insert($inserts);
    }
}
