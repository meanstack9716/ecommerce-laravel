<?php

namespace App\Enums;
use App\Constants\Constants;


enum PaymentType: string
{
    case CASH_ON_DELIVERY = Constants::CASH_ON_DELIVERY_PAYMENT;
    case RAZORPAY = Constants::RAZOR_PAY_PAYMENT;

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
            self::CASH_ON_DELIVERY->value => Constants::CASH_ON_DELIVERY_PAYMENT,
            self::RAZORPAY->value => Constants::RAZOR_PAY_PAYMENT,
        ];
    }
}