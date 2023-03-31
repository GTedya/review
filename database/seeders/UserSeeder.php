<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                'name' => 'admin',
                'email' => 'admin@admin.dev',
                'password' => Hash::make('admin%dev'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
        ];

        DB::table('users')->insert($inserts);
    }
}
