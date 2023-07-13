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
                'phone' => '78888888881',
                'email' => 'admin@admin.dev',
                'password' => Hash::make('admin%dev'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'id' => 2,
                'name' => 'dealer',
                'phone' => '78888888882',
                'email' => 'dealer@dealer.dev',
                'password' => Hash::make('dealer%dev'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'id' => 3,
                'name' => 'leasing',
                'phone' => '78888888883',
                'email' => 'leasing@leasing.dev',
                'password' => Hash::make('leasing%dev'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],
            [
                'id' => 4,
                'name' => 'client',
                'phone' => '78888888884',
                'email' => 'client@client.dev',
                'password' => Hash::make('client%dev'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ],

        ];

        DB::table('users')->insert($inserts);
    }
}
