<?php

namespace App\Enums;

enum RoleEnums: int
{
    case STUDENT = 1;    // 0001
    case TEACHER = 3;    // 0011
    case MODERATOR = 7;  // 0111
    case ADMIN = 15;     // 1111

    public function hasPermission(int $permission): bool
    {
        return ($this->value & $permission) === $permission;
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::STUDENT => 'Ученик',
            self::TEACHER => 'Преподаватель',
            self::MODERATOR => 'Модератор',
            self::ADMIN => 'Администратор',
        };
    }
}
