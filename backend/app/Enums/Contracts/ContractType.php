<?php

namespace App\Enums\Contracts;

enum ContractType: string
{
    case Electricity = 'electricity';
    case Gas         = 'gas';
    case Mobile      = 'mobile';

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
