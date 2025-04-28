<?php

namespace App\Enums;

enum AddressType: string
{
    case HOME = 'Home';
    case OFFICE = 'Office';
    case STORE = 'Store';
    case WAREHOUSE = 'Warehouse';
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
            self::HOME->value => 'Home',
            self::OFFICE->value => 'Office',
            self::STORE->value => 'Store',
            self::WAREHOUSE->value => 'Warehouse',
            self::OTHER->value => 'Other',
        ];
    }
}