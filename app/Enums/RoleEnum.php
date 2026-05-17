<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

   
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values(), true);
    }
}