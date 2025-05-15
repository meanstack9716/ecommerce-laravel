<?php

namespace App\Enums;

enum Color: string
{
    case BLACK = 'Black';
    case WHITE = 'White';
    case RED = 'Red';
    case BLUE = 'Blue';
    case GREEN = 'Green';
    case YELLOW = 'Yellow';
    case PINK = 'Pink';
    case GRAY = 'Gray';
    case NAVY = 'Navy';
    case BEIGE = 'Beige';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function hexCodes(): array
    {
        return [
            self::BLACK->value => '#000000',
            self::WHITE->value => '#FFFFFF',
            self::RED->value => '#FF0000',
            self::BLUE->value => '#0000FF',
            self::GREEN->value => '#008000',
            self::YELLOW->value => '#FFFF00',
            self::PINK->value => '#FFC0CB',
            self::GRAY->value => '#808080',
            self::NAVY->value => '#000080',
            self::BEIGE->value => '#F5F5DC',
        ];
    }

    public static function getHexCode(string $color): ?string
    {
        return self::hexCodes()[$color] ?? null;
    }

    public static function allHexCodes(): array
    {
        return array_map(fn($color) => self::getHexCode($color->value), self::cases());
    }
}