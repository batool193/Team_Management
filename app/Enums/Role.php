<?php

namespace App\Enums;

/**
 * Enum representing user roles.
 */
enum Role: string
{
    case Manager = 'manager';
    case Developer = 'developer';

        case Tester = 'tester';

    /**
     * Get all role values.
     *
     * @return array The array of role values.
     */
    public static function values(): array
    {
        return [
            self::Manager,
            self::Developer,
            self:: Tester,
        ];
    }
}
