<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'Pending';
    case CONFIRMED = 'Confirmed';
    case PROCESSING = 'Processing';
    case SHIPPED = 'Shipped';
    case DELIEVERED = 'Delivered';
    case CANCELLED = 'Cancelled';
    case RETURNED = 'Returned';
    case REFUNDED = 'Refunded';

    /**
     * Get all available address types as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all available address types as a key-value array
     */
    public static function options(): array
    {
        return [
            self::PENDING->value => 'Pending',
            self::CONFIRMED->value => 'Confirmed',
            self::PROCESSING->value => 'Processing',
            self::SHIPPED->value => 'Shipped',
            self::DELIEVERED->value => 'Delivered',
            self::CANCELLED->value => 'Cancelled',
            self::RETURNED->value => 'Returned',
            self::REFUNDED->value => 'Refunded',
        ];
    }
}