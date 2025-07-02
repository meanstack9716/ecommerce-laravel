<?php

namespace App\Enums;
use App\Constants\Constants;

enum ReturnRequestStatus: string
{
    case PENDING = Constants::STATUS_PENDING;
    case APPROVED = Constants::STATUS_APPROVED;
    case REJECTED = Constants::STATUS_REJECTED;
    case REFUNDED = Constants::STATUS_REFUNDED;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return [
            self::PENDING->value => Constants::STATUS_PENDING,
            self::APPROVED->value => Constants::STATUS_APPROVED,
            self::REJECTED->value => Constants::STATUS_REJECTED,
            self::REFUNDED->value => Constants::STATUS_REFUNDED,
        ];
    }
}