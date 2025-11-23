<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AdvancedSettings extends Settings
{
    public bool $allow_decimal_quantities;

    public string $default_discount_method;

    public array $payment_methods;

    public function payment_methods_ids()
    {
        return array_map(function ($method) {
            return $method === 'cash'
                ? \App\Enums\PaymentTypeEnum::cash->value
                : \App\Enums\PaymentTypeEnum::debt->value;
        }, $this->payment_methods);
    }


    public static function group(): string
    {
        return 'advanced';
    }
}
