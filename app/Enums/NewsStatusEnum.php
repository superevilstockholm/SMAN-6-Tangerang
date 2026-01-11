<?php

namespace App\Enums;

enum NewsStatusEnum: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case SCHEDULED = 'scheduled';
}
