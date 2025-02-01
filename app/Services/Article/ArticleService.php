<?php

namespace App\Services\Article;

use App\Enums\ArticleStatusEnums;
use App\Enums\ArticleTypeEnums;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function get(Request $request)
    {
        $type = ArticleTypeEnums::tryFrom((int) $request->query('type'));
        $user = Auth::guard('sanctum')->user();
        $query = Article::query();


        if ($request->has('type')) {
            $type = ArticleTypeEnums::tryFrom((int) $request->query('type'));

            if ($user && $type) {
                switch ($type) {
                    case ArticleTypeEnums::PASSING:
                        $query = $user->passing();
                        break;
                    case ArticleTypeEnums::FAVORITES:
                        return;
                }
            }
        }

        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->whereRaw("CONCAT(first_name, ' ', COALESCE(surname, '')) LIKE ?", ["%{$search}%"]);
                });
        }
        if ($certificate = $request->query('has_certificate')) {
            if ($certificate) {
                $query->where('has_certificate', 1);
            }
        }

        return $query;
    }
}
