<?php

namespace App\Services\Article;

use App\Enums\ArticleStatusEnums;
use App\Enums\ArticleTypeEnums;
use App\Enums\CertificateEnums;
use App\Models\Article;
use App\Models\Certificate;
use App\Models\CertificateType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as FacadesLog;
use Log;
use ValueError;

class CertificateService
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param [type] $getting_type - 0 получение сертификатов, 1 получение списка сертификатов (certificate_types) пользователей
     * @return \Illuminate\Database\Eloquent\Builder<static>
     */
    public function get(Request $request, int $getting_type)
    {
        $query = $getting_type == 0 ? $this->getCertificates($request) : $this->getCertificateTypes($request);

        return $query;
    }

    private static function getCertificates(Request $request)
    {

        $user = Auth::guard('sanctum')->user();
        $query = $user->certificates();


        if ($search = $request->query('search')) {
            $query->whereHas('article', function ($query) use ($search) {
                $query->whereRaw("title LIKE ?", ["%{$search}%"]);
            });
        }

        return $query;
    }
    private static function getCertificateTypes(Request $request)
    {
        $query = CertificateType::query();

        if ($request->my == 1) {
            $user = Auth::guard('sanctum')->user();
            $query = $user->certificate_types();
        }

        if ($request->has('type')) {
            $type = CertificateEnums::tryFrom((int) $request->query('type'));
            $query = CertificateType::where('status', $type);
        }

        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->whereRaw("CONCAT(first_name, ' ', COALESCE(surname, '')) LIKE ?", ["%{$search}%"]);
                });
        }

        return $query;
    }
}
