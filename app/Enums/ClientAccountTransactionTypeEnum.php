<?php

namespace App\Enums;

enum ClientAccountTransactionTypeEnum: int
{
    case credit = 1;
    case debit = -1;

    public function label(): string
    {
        return match($this) {
            ClientAccountTransactionTypeEnum::credit => __('trans.credit'),
            ClientAccountTransactionTypeEnum::debit => __('trans.debit'),
        };
    }

    public function style()
    {
        return match($this) {
            ClientAccountTransactionTypeEnum::credit => 'success',
            ClientAccountTransactionTypeEnum::debit => 'danger',
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
