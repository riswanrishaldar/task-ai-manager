<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'inprogress';
    case COMPLETED = 'completed';

    /**
     * Get all enum values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if a given value is valid status
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values(), true);
    }
}
