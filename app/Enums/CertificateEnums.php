<?php

namespace App\Enums;

enum CertificateEnums: int
{
    case MODERATION = 0;
    case ACTIVE = 1;
    case REJECTED = 2;

    public function getDescription(): string
    {
        return match ($this) {
            self::MODERATION => 'На модерации',
            self::ACTIVE => 'Активный',
            self::REJECTED => 'Отклонено модератором',
        };
    }
}
