<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Verified = 'verified';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Verified => 'Verified',
            self::Failed => 'Failed',
        };
    }
}
