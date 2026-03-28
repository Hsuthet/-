<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminUserSeeder extends Seeder

{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // 1. CREATE SUPER ADMIN
        User::create([
            'name' => 'システム管理者', 
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'department_id' => null,
        ]);
    }
}
