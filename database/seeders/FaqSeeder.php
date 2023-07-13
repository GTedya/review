<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            [
                'id' => 1,
                'question' => 'Почему мы?',
                'answer' => 'Сопровождаем сделку под ключ'
            ],
            [
                'id' => 2,
                'question' => 'Где мы находимся?',
                'answer' => 'г.Владикавказ ул. Шмулевича 8Б'
            ],
        ];

        DB::table('faqs')->insert($inserts);
    }
}
