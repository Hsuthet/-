<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $surnames = ['佐藤', '鈴木', '高橋', '田中', '伊藤', '渡辺'];
        $givenNames = ['太郎', '次郎', '花子', '一郎', '和子'];
        
        $globalCounter = 1; // Start at 1

        // 1. CREATE SUPER ADMIN
        User::create([
            'name' => 'システム管理者', 
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'employee_number' => 'EMP-' . str_pad($globalCounter++, 3, '0', STR_PAD_LEFT),
            'department_id' => null,
        ]);

        // 2. Loop through 6 departments
        for ($deptId = 1; $deptId <= 6; $deptId++) {
            
            // --- CREATE 1 MANAGER ---
            User::create([
                'name' => $surnames[$deptId - 1] . '（課長）',
                'email' => "manager" . $deptId . "@example.com",
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'employee_number' => 'EMP-' . str_pad($globalCounter++, 3, '0', STR_PAD_LEFT),
                'department_id' => $deptId,
            ]);

            // --- CREATE 3 EMPLOYEES PER DEPT ---
            for ($i = 1; $i <= 3; $i++) {
                User::create([
                    'name' => $surnames[array_rand($surnames)] . ' ' . $givenNames[array_rand($givenNames)],
                    'email' => "user" . $globalCounter . "@example.com", 
                    'password' => Hash::make('password123'),
                    'role' => 'employee',
                    'employee_number' => 'EMP-' . str_pad($globalCounter++, 3, '0', STR_PAD_LEFT),
                    'department_id' => $deptId,
                ]);
            }
        }
    }
}