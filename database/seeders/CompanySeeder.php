<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            [
                'id' => 1,
                'inn' => '151501533302',
                'org_type' => 'ИП',
                'org_name' => 'ИП Тотров Аркадий Вячеславович',
                'user_id' => 4,
                'geo_id' => 1
            ],
        ];

        DB::table('companies')->insert($inserts);
    }
}
