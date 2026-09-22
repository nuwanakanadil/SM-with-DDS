<?php

namespace App\Enums;

enum ClassGroups: string
{
    case Walala = 'Walala';
    case Mathale = 'Mathale';

    public static function values(): array
    {
        return array_map(static fn (self $group) => $group->value, self::cases());
    }
}
