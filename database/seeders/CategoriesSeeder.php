<?php

namespace Database\Seeders;

use App\Enums\CategoryStatusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i<=10; $i++) {
            \App\Models\Category::updateOrCreate([
                'name' => 'Category ' . $i,
            ], [
                'name' => 'Category ' . $i,
                'status' => CategoryStatusEnum::active,
            ]);
        }
    }
}
