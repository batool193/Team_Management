<?php

namespace App\Enums;

/**
 * Enum representing task status.
 */
enum TaskStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    /**
     * Get all status values.
     *
     * @return array The array of status values.
     */
    public static function values(): array
    {
        return [
            self::New,
            self::InProgress,
            self::Completed,
        ];
    }
}
