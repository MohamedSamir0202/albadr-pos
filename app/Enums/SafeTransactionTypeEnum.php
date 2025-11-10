<?php

namespace App\Enums;

enum SafeTransactionTypeEnum: int
{
    case in = 1;
    case out = -1;


    public function label(): string
    {
        return match($this) {
            SafeTransactionTypeEnum::in => __('trans.cash_in'),
            SafeTransactionTypeEnum::out => __('trans.cash_out'),
        };
    }

    public function style()
    {
        return match($this) {
            SafeTransactionTypeEnum::in => 'success',
            SafeTransactionTypeEnum::out => 'danger',
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
