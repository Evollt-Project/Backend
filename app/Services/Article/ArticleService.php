<?php

namespace App\Services\Article;

use App\Enums\ArticleStatusEnums;
use App\Enums\ArticleTypeEnums;
use App\Models\Article;
use App\Services\Base\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use ValueError;

class ArticleService extends Service
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
        $user = Auth::guard('sanctum')->user();
        $filters = $request->all();
        $query = Article::query();

        if (isset($filters['type']) && $user) {
            $type = ArticleTypeEnums::tryFrom((int) $filters['type']);

            $query = match ($type) {
                ArticleTypeEnums::PASSING => $user->passing(),
                ArticleTypeEnums::FAVORITES => $user->favorites(),
                ArticleTypeEnums::WANT_TO_PASS => $user->want_to_pass(),
                default => Article::query()
            };
        }

        return $this->applyFilters($query, $filters);
    }

    public function applyFilters(Builder $query, array $filters): Builder
    {
        // Поиск по тексту
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                collect([
                    'title',
                    'short_content',
                    'about_content',
                ])->each(fn($field) => $q->orWhere($field, 'like', "%{$search}%"));

                $q->orWhereHas('user', function ($q) use ($search) {
                    $q->whereRaw("CONCAT(first_name, ' ', COALESCE(surname, '')) LIKE ?", ["%{$search}%"]);
                });
            });
        }

        // Фильтр по сертификату
        if (isset($filters['has_certificate']) && (bool) $filters['has_certificate']) {
            $query->where('has_certificate', true);
        }

        // Фильтр по статусу
        if (!empty($filters['status'])) {
            $statusEnum = ArticleStatusEnums::tryFrom((int) $filters['status']);
            if ($statusEnum) {
                $query->where('status', $statusEnum->value);
            }
        }

        return $query;
    }
}
