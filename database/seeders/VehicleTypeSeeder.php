<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            [
                'id' => 1,
                'name' => 'Тракторы',
            ],
            [
                'id' => 2,
                'name' => 'Самосвалы',
            ],
            [
                'id' => 3,
                'name' => 'Погрузочные машины',
            ],

        ];

        DB::table('vehicle_types')->insert($inserts);
    }
}
