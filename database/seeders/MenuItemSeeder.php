<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inserts = [
            [
                'id' => 1,
                'name' => 'Страница подбора',
                'link' => '/search',
                'group_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'О компаннии',
                'link' => '/about',
                'group_id' => 2,
            ],
            [
                'id' => 3,
                'name' => 'Лизинг',
                'link' => '/leasings',
                'group_id' => 2,
            ],

        ];

        DB::table('menu_items')->insert($inserts);
    }
}
