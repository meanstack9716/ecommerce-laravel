<?php

namespace App\Enums;

enum IdentificationType: string
{
    case AADHAR_CARD = 'Aadhar Card';
    case DRIVING_LICENSE = 'Driving License';
    case PASSPORT = 'Passport';
    case RATION_CARD = 'Ration Card';

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
            self::AADHAR_CARD->value => 'Aadhar Card',
            self::DRIVING_LICENSE->value => 'Driving License',
            self::PASSPORT->value => 'Passport',
            self::RATION_CARD->value => 'Ration Card',
        ];
    }
}