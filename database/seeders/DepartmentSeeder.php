<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            '技術課',
            '開発課',
            'サービス課',
            '製造課',
            'データ制作課',
            'DD課',
        ];

        foreach ($departments as $name) {
          
            Department::updateOrCreate(['name' => $name]);
        }
    }
}
