<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            [
                'id' => 1,
                'user_id' => '4',
                'name' => 'Аркадий Вячеславович',
                'inn' => '151501533302',
                'phone' => '88005553535',
                'email' => 'georgi@mail.ru',
                'created_at' => now(),
                'updated_at' => now()->addMonth(),
            ],

        ];
        DB::table('orders')->insert($inserts);
    }
}
