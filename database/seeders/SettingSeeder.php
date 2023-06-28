<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            [
                'id' => 1,
                'email' => 'bbidarov@gmail.com',
                'phone' => '+7 (495) 287-42-34',

            ],
        ];

        DB::table('settings')->insert($inserts);
    }
}
