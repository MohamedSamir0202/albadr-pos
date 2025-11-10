<?php

namespace App\Enums;

enum StockStatusEnum: int
{
    case active = 1;
    case inactive = 2;


    public function label(): string
    {
        return match($this) {
            StockStatusEnum::active => __('trans.active'),
            StockStatusEnum::inactive => __('trans.inactive'),
        };
    }

    public function style()
    {
        return match($this) {
            StockStatusEnum::active => 'success',
            StockStatusEnum::inactive => 'danger',
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
