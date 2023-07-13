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
                'name' => 'Тракторы',
                'link' => '/traktori',
                'group_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Ne Тракторы',
                'link' => '/netraktori',
                'group_id' => 2,
            ],

        ];

        DB::table('menu_items')->insert($inserts);
    }
}
