<?php

namespace App\Enums;

enum CartStatusEnum: int
{
    case active = 1;
    case inactive = 2;


    public function label(): string
    {
        return match($this) {
            CartStatusEnum::active => ('active'),
            CartStatusEnum::inactive => ('inactive'),
        };
    }

    public function style()
    {
        return match($this) {
            CartStatusEnum::active => 'success',
            CartStatusEnum::inactive => 'danger',
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
