<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Managers for each department (1-6)
        $managerNames = ['佐藤', '鈴木', '高橋', '田中', '伊藤', '渡辺'];
        foreach ($managerNames as $index => $name) {
            User::create([
                'name' => $name . " (管理者)",
                'email' => "manager" . ($index + 1) . "@gmail.com",
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'department_id' => $index + 1,
            ]);
        }

        // 2. Kanji Name Pools
        $surnames = ['佐藤', '鈴木', '高橋', '田中', '渡辺', '伊藤', '中村', '小林', '加藤', '吉田', '山田', '佐々木'];
        $givenNames = ['太郎', '健一', '浩', '和也', '陽子', '美智子', '大輔', '直樹', '恵', '健太'];

        // 3. Create 100 Employees
        for ($i = 1; $i <= 100; $i++) {
            $fName = $surnames[array_rand($surnames)];
            $lName = $givenNames[array_rand($givenNames)];
            
            User::create([
                'name' => $fName . ' ' . $lName, // Example: 田中 太郎
                'email' => "employee$i@test.com",
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'department_id' => rand(1, 6),
            ]);
        }
    }
}