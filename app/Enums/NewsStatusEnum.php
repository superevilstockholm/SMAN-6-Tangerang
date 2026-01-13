<?php

namespace App\Enums;

enum NewsStatusEnum: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case SCHEDULED = 'scheduled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Dipublikasikan',
            self::SCHEDULED => 'Terjadwal',
        };
    }
}
