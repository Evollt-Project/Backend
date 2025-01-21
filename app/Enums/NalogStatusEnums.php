<?php

namespace App\Enums;

enum NalogStatusEnums: int
{
    case PHYSICAL = 0;
    case INDIVIDUAL = 1;
    case JURIDICAL = 2;
    case SELF_EMPLOYED = 3;
}
