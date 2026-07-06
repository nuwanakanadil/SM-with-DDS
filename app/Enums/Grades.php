<?php

namespace App\Enums;

enum Grades: string
{
    case Grade6 = 'Grade 6';
    case Grade7 = 'Grade 7';
    case Grade8 = 'Grade 8';
    case Grade9 = 'Grade 9';
    case Grade10 = 'Grade 10';
    case Grade11 = 'Grade 11';

    public static function values(): array
    {
        return array_map(static fn (self $grade) => $grade->value, self::cases());
    }
}
