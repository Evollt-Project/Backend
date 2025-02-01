<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Enums\ArticleTypeEnums;
use App\Enums\RoleEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequisiteRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\UserResource;
use App\Models\Requisite;
use App\Models\Skill;
use App\Models\User;
use App\Services\User\RequisiteService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function get(): JsonResponse
    {
        $user = Auth::user();

        return response()->json(new UserResource($user), 200);
    }

    public function getById($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user || $user->privacy) {
            return response()->json([
                'message' => 'Пользователь или не существует или предпочел скрыть свой профиль',
            ]);
        }

        return response()->json(new UserResource($user));
    }

    public function skills(): JsonResponse
    {
        return response()->json(Skill::all());
    }

    public function update(Request $request, RequisiteService $requisite)
    {
        $user = Auth::user();

        foreach ($user->fillable as $field) {
            if ($request->has($field)) {
                $user->$field = $request->$field;
            }
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                $path = $file->store($user->id, 'public');
                $user->avatar = \Storage::disk('public')->url($path);
            }
        }

        $skillIds = json_decode($request->input('skill_ids', '[]'));

        $skills = Skill::whereIn('id', $skillIds)->get();
        $user->skills()->sync($skills);

        $user->save();

        if ($request->requisites) {
            $this->createOrUpdateRequisites($request, $requisite);
        }

        return response()->json(new UserResource($user), 200);
    }

    public function createOrUpdateRequisites(Request $request, RequisiteService $requisite)
    {
        $request->validate([
            'requisites.nalog_status' => 'required|integer',
            'requisites.date_of_birth' => 'date_format:Y-m-d',
            'requisites.passport' => 'size:11',
            'requisites.inn' => 'required|string',
            'requisites.fio' => 'required|string',
            'requisites.bik' => 'required|string|size:9',
            'requisites.bank' => 'required|string',
            'requisites.payment_account' => 'required|string',
        ]);
        return $requisite->createOrUpdate($request->requisites);
    }

    public function enums(): JsonResponse
    {
        $enums = [
            'roles' => collect(RoleEnums::cases())
                ->mapWithKeys(fn($case) => [$case->value => $case->getDescription()]),
            'article_types' => collect(ArticleTypeEnums::cases())
                ->mapWithKeys(fn($case) => [$case->value => $case->getDescription()])
        ];

        return response()->json($enums);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validatedData['old_password'], $user->password)) {
            return response()->json([
                'message' => 'Старый пароль написан неверно',
                'status' => false
            ], 422);
        }

        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return response()->json([
            'message' => 'Пароль успешно изменен',
            'status' => true
        ]);
    }
}
