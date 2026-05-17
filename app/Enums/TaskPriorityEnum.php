<?php

namespace App\Enums;

enum TaskPriorityEnum: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    /**
     * Get all enum values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if a given value is valid priority
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values(), true);
    }
}