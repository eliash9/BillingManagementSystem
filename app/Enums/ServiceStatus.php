<?php

namespace App\Enums;

enum ServiceStatus: string
{
    case Active = 'active';
    case Due = 'due';
    case Overdue = 'overdue';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Due => 'Due',
            self::Overdue => 'Overdue',
            self::Suspended => 'Suspended',
        };
    }
}
