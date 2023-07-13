<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            [
                'id' => 1,
                'title' => 'Тракторы',
                'slug' => '/traktori',
                'content' => 'Тракторы',
                'start_date' => now(),
                'end_date' => now()->addMonth(),
            ],

        ];

        DB::table('news')->insert($inserts);
    }
}
