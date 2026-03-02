<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
        '作図', '設計', '調査', '流通', '資料',
        '設理', '製作', '振品', '変更', '回答',
        '再生', '製品', '工事', '書込み', 'データ'
    ];

    foreach ($categories as $name) {
        Category::updateOrCreate(['name' => $name]);
    }
    }
}
