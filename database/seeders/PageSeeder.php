<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dateTime = now();

        $inserts = [
            [
                'id' => 1,
                'template' => 'main',
                'slug' => 'main',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'id' => 2,
                'template' => 'search',
                'slug' => 'search',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'id' => 3,
                'template' => 'about',
                'slug' => 'about',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'id' => 4,
                'template' => 'leasings',
                'slug' => 'leasings',
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],

        ];

        DB::table('pages')->insert($inserts);
    }
}
