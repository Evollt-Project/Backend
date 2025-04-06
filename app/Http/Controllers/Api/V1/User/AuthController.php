<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Enums\RolesEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Jobs\User\SendPhoneVerificationCodeJob;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = new User;

        foreach ($user->fillable as $field) {
            if ($request->has($field)) {
                $user->$field = $request->$field;
            }
        }

        $user->save();

        SendPhoneVerificationCodeJob::dispatch($user);

        return response()->json(
            array_merge(
                (new UserResource($user))->toArray(request()),
//                [
//                    'token' => $user->createToken('API TOKEN')->plainTextToken // Добавляем токен
//                ]
            ),
            200
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Пользователь с такой почтой или паролем не был найден',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json(
            array_merge(
                (new UserResource($user))->toArray(request()), // Преобразуем ресурс в массив
                [
                    'token' => $user->createToken('API TOKEN')->plainTextToken // Добавляем токен
                ]
            ),
            200
        );
    }


    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'status' => true,
        ], 200);
    }
}
