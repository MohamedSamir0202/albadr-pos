<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case CONFIRMED  = 'confirmed';
    case PROCESSING = 'processing';
    case SHIPPED    = 'shipped';
    case DELIVERED  = 'delivered';
}
