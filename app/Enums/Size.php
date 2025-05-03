<?php

namespace App\Enums;

enum Size: string
{
    case XS = 'XS';
    case S = 'S';
    case M = 'M';
    case L = 'L';
    case XL = 'XL';
    case XXL = 'XXL';
    case XXXL = 'XXXL';
    
    case SIZE_28 = '28';
    case SIZE_30 = '30';
    case SIZE_32 = '32';
    case SIZE_34 = '34';
    case SIZE_36 = '36';
    case SIZE_38 = '38';
    case SIZE_40 = '40';
    case SIZE_42 = '42';
    case SIZE_44 = '44';
    case SIZE_46 = '46';
    case SIZE_48 = '48';
    case SIZE_50 = '50';
    

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function standardSizes(): array
    {
        return [
            self::XS->value,
            self::S->value,
            self::M->value,
            self::L->value,
            self::XL->value,
            self::XXL->value,
            self::XXXL->value,
        ];
    }

    public static function numericSizes(): array
    {
        return [
            self::SIZE_28->value,
            self::SIZE_30->value,
            self::SIZE_32->value,
            self::SIZE_34->value,
            self::SIZE_36->value,
            self::SIZE_38->value,
            self::SIZE_40->value,
            self::SIZE_42->value,
            self::SIZE_44->value,
            self::SIZE_46->value,
            self::SIZE_48->value,
            self::SIZE_50->value,
        ];
    }

    public static function isStandardSize(string $size): bool
    {
        return in_array($size, self::standardSizes());
    }

    public static function isNumericSize(string $size): bool
    {
        return in_array($size, self::numericSizes());
    }
}