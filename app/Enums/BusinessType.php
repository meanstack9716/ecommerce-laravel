<?php

namespace App\Enums;

enum BusinessType: string
{
    case SOLE = 'Sole Proprietorship';
    case PARTNERSHIP = 'Partnership';
    case CORPORATION = 'Corporation';
    case OTHER = 'Other';

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
            self::SOLE->value => 'Sole Proprietorship',
            self::PARTNERSHIP->value => 'Partnership',
            self::CORPORATION->value => 'Corporation',
            self::OTHER->value => 'Other',
        ];
    }
}