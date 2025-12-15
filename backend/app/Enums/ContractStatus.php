<?php

namespace App\Enums;

enum ContractStatus: string
{
    case Active = 'active';
    case Closed = 'closed';

    /**
     * Get all enum values as array for validation.
     */
    public static function values(): array
    {
        return array_map(fn($c) => $c->value, self::cases());
    }

    /**
     * Check if a value is a valid enum value.
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values(), true);
    }
}
