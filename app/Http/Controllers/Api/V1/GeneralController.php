<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ArticleTypeEnums;
use App\Enums\CertificateEnums;
use App\Enums\RoleEnums;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\LevelResource;
use App\Models\Language;
use App\Models\Level;
use Illuminate\Http\JsonResponse;

class GeneralController extends Controller
{
    public function enums(): JsonResponse
    {
        $enums = [
            'roles' => collect(RoleEnums::cases())
                ->mapWithKeys(fn($case) => [$case->value => $case->getDescription()]),
            'article_types' => collect(ArticleTypeEnums::cases())
                ->mapWithKeys(fn($case) => [$case->value => $case->getDescription()]),
            'certificate_types' => collect(CertificateEnums::cases())
                ->mapWithKeys(fn($case) => [$case->value => $case->getDescription()]),
            'languages' => LanguageResource::collection(Language::all()),
            'levels' => LevelResource::collection(Level::all())
        ];

        return response()->json($enums);
    }


}
