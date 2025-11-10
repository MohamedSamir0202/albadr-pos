<?php

namespace App\Enums;

enum WarehouseStatusEnum: int
{
    case active = 1;
    case inactive = 2;


    public function label(): string
    {
        return match($this) {
            WarehouseStatusEnum::active => __('trans.active'),
            WarehouseStatusEnum::inactive => __('trans.inactive'),
        };
    }

    public function style()
    {
        return match($this) {
            WarehouseStatusEnum::active => 'success',
            WarehouseStatusEnum::inactive => 'danger',
        };
    }

    public static function labels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[$case->value] = $case->label();
        }
        return $labels;
    }

}
