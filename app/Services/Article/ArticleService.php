<?php

namespace App\Services\Article;

use App\Enums\ArticleStatusEnums;
use Log;
use ValueError;

class ArticleService
{
    public function isValidStatus(int $status): bool
    {
        try {
            ArticleStatusEnums::from($status);
            return true;
        } catch (ValueError $e) {
            Log::error($e);
            return false;
        }
    }
}
