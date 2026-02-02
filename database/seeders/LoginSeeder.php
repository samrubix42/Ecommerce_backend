<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoginSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Techonika User',
            'email' => 'admin@techonika.com',
            'password' => bcrypt('123456789'), // Use a secure password
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}