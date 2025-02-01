<?php

namespace App\Enums;

enum ArticleTypeEnums: int
{
    case PASSING = 0;
    case FAVORITES = 1;
    case WANT_TO_PASS = 2;
    public function getDescription(): string
    {
        return match ($this) {
            self::PASSING => 'Прохожу',
            self::FAVORITES => 'Избранное',
            self::WANT_TO_PASS => 'Хочу пройти',
        };
    }
}
