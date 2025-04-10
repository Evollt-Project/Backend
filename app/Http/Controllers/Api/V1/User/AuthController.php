<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Enums\RolesEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Jobs\User\SendEmailVerificationMailJob;
use App\Jobs\User\SendPhoneVerificationCodeJob;
use App\Models\User;
use App\Services\Sms\SmsRuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


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

        SendEmailVerificationMailJob::dispatch($user);

        return response()->json(
            new UserResource($user),
            200
        );
    }

    public function approveEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string)$hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Недопустимая ссылка'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email уже подтверждён']);
        }

        $user->markEmailAsVerified();
        $user->email_verified = true;
        $user->save();

        // Создаём токен
        $token = $user->createToken('YourAppName')->plainTextToken;

        return response()->json([
            'message' => 'Email успешно подтверждён',
            'token' => $token,
        ]);
    }

    public function emailResend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Письмо отправлено повторно']);
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

        // 💡 Проверка, подтвержден ли email
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return response()->json(['message' => 'Аккаунт не подтвержден'], 403);
        }

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

    public function code(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => 'required|string',
        ]);
        SendPhoneVerificationCodeJob::dispatch($validated['phone']);

        return response()->json([
            'status' => true,
        ], 200);
    }

    public function codeCheck(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string'
        ]);

        $smsService = new SmsRuService();
        $status = $smsService->checkVerificationCode($validated['phone'], $request->code);

        return response()->json([
            'status' => $status,
        ], 200);
    }

    public function emailCheckExists(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $exists = User::where('email', $request->input('email'))->exists();

        return response()->json([
            'exists' => $exists,
        ]);
    }


    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'status' => true,
        ], 200);
    }
}
