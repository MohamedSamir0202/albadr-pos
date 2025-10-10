<?php

namespace App\Enums;

enum ClientStatusEnum: int
{
    case active = 1;
    case inactive = 2;


    public function label(): string
    {
        return match($this) {
            ClientStatusEnum::active => __('trans.active'),
            ClientStatusEnum::inactive => __('trans.inactive'),
        };
    }

    public function style()
    {
        return match($this) {
            ClientStatusEnum::active => 'success',
            ClientStatusEnum::inactive => 'danger',
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
