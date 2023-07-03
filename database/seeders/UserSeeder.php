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
                'phone' => '88888888881',
                'email' => 'admin@admin.dev',
                'password' => Hash::make('admin%dev'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
                'inn' => '123456789011'
            ],
            [
                'id' => 2,
                'name' => 'dealer',
                'phone' => '88888888882',
                'email' => 'dealer@dealer.dev',
                'password' => Hash::make('dealer%dev'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
                'inn' => '123456789012'
            ],
            [
                'id' => 3,
                'name' => 'leasing',
                'phone' => '88888888883',
                'email' => 'leasing@leasing.dev',
                'password' => Hash::make('leasing%dev'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
                'inn' => '123456789013'
            ],
            [
                'id' => 4,
                'name' => 'client',
                'phone' => '88888888884',
                'email' => 'client@client.dev',
                'password' => Hash::make('client%dev'),
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
                'inn' => '123456789014'
            ],

        ];

        DB::table('users')->insert($inserts);
    }
}
