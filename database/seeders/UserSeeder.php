<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nickname' => 'super-admin',
            'given_name' => 'Admin',
            'family_name' => 'Super',
            'email' => 'super-admin@example.com',
            'company_id' => 0,
            'user_type' => 'super-user',
            'status' => 'active',
            'password' => 'password1'
        ]);

        User::create([
            'nickname' => 'admin',
            'given_name' => 'Admin',
            'family_name' => 'User',
            'email' => 'admin@example.com',
            'company_id' => 0,
            'user_type' => 'administrator',
            'status' => 'active',
            'password' => 'password2'
        ]);
    }
}
