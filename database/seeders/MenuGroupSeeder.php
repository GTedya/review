<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuGroupSeeder extends Seeder
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
                'is_bottom' => false,
            ],
            [
                'id' => 2,
                'name' => 'Ne Тракторы',
                'link' => '/netraktori',
                'is_bottom' => true,
            ],

        ];

        DB::table('menu_groups')->insert($inserts);
    }
}
