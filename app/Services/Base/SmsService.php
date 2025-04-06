<?php

namespace App\Services\Base;

use App\Models\ConfirmedPhone;
use App\Models\PhoneVerificationCode;
use App\Models\User;

abstract class SmsService
{
    protected string $url;
    protected string $apiToken;

    public function __construct()
    {
        $this->url = config('services.sms.url');
        $this->apiToken = config('services.sms.api_key');
    }

    abstract public function send(array $phones, string $message, string $ip): array;

    protected function parseResponse(array $data): array
    {
        $results = [];

        foreach ($data['sms'] ?? [] as $phone => $info) {
            $results[] = [
                'phone' => $phone,
                'status' => $info['status'],
                'code' => $info['status_code'],
                'message' => $info['status_text'] ?? null,
                'sms_id' => $info['sms_id'] ?? null,
            ];
        }

        return [
            'status' => $data['status'] ?? 'UNKNOWN',
            'status_code' => $data['status_code'] ?? null,
            'balance' => $data['balance'] ?? null,
            'results' => $results,
        ];
    }

    public function createVerificationCode(User|null $user = null): PhoneVerificationCode
    {
        $code = rand(100000, 999999); // или любой другой способ генерации
        $verificationCode = PhoneVerificationCode::create([
            'user_id' => $user->id,
            'ip' => request()->ip(),
            'phone' => $user->phone,
            'code' => $code,
            'expired_at' => now()->addMinutes(10),
        ]);

        return $verificationCode;
    }

    public function checkVerificationCode(User|null $user, string $code)
    {
        $record = PhoneVerificationCode::where('code', $code)
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$record) {
            return false;
        }

        ConfirmedPhone::create([
            'user_id' => $user->id,
            'ip' => $record->ip,
            'phone' => $record->phone,
        ]);

        $record->delete();

        $user->update([
            'phone_verified' => true,
            'phone_verified_at' => now(),
        ]);

        return true;
    }
}
