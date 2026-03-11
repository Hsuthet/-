<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $departments = [1,2,3,4,5]; // IDs of your departments

        // Create 5 managers
        foreach ($departments as $deptId) {
            User::create([
                'name' => "Manager Dept $deptId",
                'email' => "manager$deptId@example.com",
                'password' => bcrypt('password123'),
                'role' => 'manager',
                'department_id' => $deptId,
            ]);
        }

        // Create 5 employees
        foreach ($departments as $deptId) {
            User::create([
                'name' => "Employee Dept $deptId",
                'email' => "employee$deptId@example.com",
                'password' => bcrypt('password123'),
                'role' => 'employee',
                'department_id' => $deptId,
            ]);
        }
    }
}
