<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            [
                'id' => 1,
                'name' => 'В обработке',
            ],
            [
                'id' => 2,
                'name' => 'На рассмотрении',
            ],
            [
                'id' => 3,
                'name' => 'Сбор заявок',
            ],
            [
                'id' => 4,
                'name' => 'Отменен',
            ],
        ];

        DB::table('statuses')->insert($inserts);
    }
}
