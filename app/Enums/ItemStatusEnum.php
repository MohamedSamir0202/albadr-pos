<?php

namespace App\Enums;

enum ItemStatusEnum: int
{
    case active = 1;
    case inactive = 2;


    public function label(): string
    {
        return match($this) {
            ItemStatusEnum::active => __('trans.active'),
            ItemStatusEnum::inactive => __('trans.inactive'),
        };
    }

    public function style()
    {
        return match($this) {
            ItemStatusEnum::active => 'success',
            ItemStatusEnum::inactive => 'danger',
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
