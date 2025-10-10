<?php

namespace Database\Seeders;

use App\Enums\UnitStatusEnum;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'Piece',
            'Kilogram',
            'Liter',
            'Box',
        ];
        foreach ($units as $unit) {
            Unit::updateOrCreate([
                'name' => $unit,
            ], [
                'name' => $unit,
                'status' => UnitStatusEnum::active,
            ]);
        }
    }
}
