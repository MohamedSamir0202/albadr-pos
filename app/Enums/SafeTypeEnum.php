<?php

namespace App\Enums;

enum SafeTypeEnum: int
{
    case cash = 1;
    case online = 2;


    public function label(): string
    {
        return match($this) {
            SafeTypeEnum::cash => __('trans.cash'),
            SafeTypeEnum::online => __('trans.online'),
        };
    }

    public function style()
    {
        return match($this) {
            SafeTypeEnum::cash => 'success',
            SafeTypeEnum::online => 'info',
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
