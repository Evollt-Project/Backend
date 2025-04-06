<?php

namespace App\Services\Base;

abstract class SmsService
{
    protected string $url;
    protected string $apiToken;

    public function __construct()
    {
        $this->url = config('services.sms.url');
        $this->apiToken = config('services.sms.api_key');
    }

    abstract public function send(): array;

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
}
