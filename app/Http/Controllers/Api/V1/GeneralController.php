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
use App\Models\User;
use App\Services\Sms\SmsRuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function check(Request $request): JsonResponse
    {
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }
        $smsService = new SmsRuService();
        $smsService->checkVerificationCode($user, $request->code);

        return response()->json([
            'status' => true,
        ], 200);
    }

}
