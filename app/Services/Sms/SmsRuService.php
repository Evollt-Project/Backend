<?php

namespace App\Services\Sms;

use App\Models\User;
use App\Services\Base\SmsService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;

class SmsRuService extends SmsService
{
    protected Client $http;

    public function __construct(User|Authenticatable|null $user = null)
    {
        parent::__construct();

        $this->http = new Client([
            'base_uri' => $this->url,
        ]);
    }

    public function send(array $phones, string $message, string $ip): array
    {
        try {
            $response = $this->http->post('sms/send', [
                'form_params' => [
                    'api_id' => $this->apiToken,
                    'to' => implode(',', $phones),
                    'ip' => $ip,
                    'msg' => $message,
                    'json' => '1',
                    'test' => '1'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return $this->parseResponse($data);
        } catch (RequestException $e) {
            Log::channel('register_sms_create')->error('Ошибка при отправке SMS: ' . $e->getMessage(), [
                'user_id' => $this->user->id ?? null,
                'exception' => $e
            ]);
            return [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ];
        }
    }
}
