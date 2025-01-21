<?php

namespace App\Enums;

enum ArticleStatusEnums: int
{
    case DRAFT = 0;
    case MODERATION = 1;
    case ACTIVE = 2;
}
