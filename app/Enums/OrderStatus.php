<?php

namespace App\Enums;
use App\Constants\Constants;

enum OrderStatus: string
{
    case PENDING = Constants::STATUS_PENDING;
    case CONFIRMED = Constants::STATUS_CONFIRMED;
    case PROCESSING = Constants::STATUS_PROCESSING;
    case SHIPPED = Constants::STATUS_SHIPPED;
    case DELIEVERED = Constants::STATUS_DELIVERED;
    case CANCELLED = Constants::STATUS_CANCELLED;
    case RETURNED = Constants::STATUS_RETURNED;
    case REFUNDED = Constants::STATUS_REFUNDED;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return [
            self::PENDING->value => Constants::STATUS_PENDING,
            self::CONFIRMED->value => Constants::STATUS_CONFIRMED,
            self::PROCESSING->value => Constants::STATUS_PROCESSING,
            self::SHIPPED->value => Constants::STATUS_SHIPPED,
            self::DELIEVERED->value => Constants::STATUS_DELIVERED,
            self::CANCELLED->value => Constants::STATUS_CANCELLED,
            self::RETURNED->value => Constants::STATUS_RETURNED,
            self::REFUNDED->value => Constants::STATUS_REFUNDED,
        ];
    }
}