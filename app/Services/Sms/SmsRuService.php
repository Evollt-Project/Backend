<?php

namespace App\Services\Sms;

use App\Services\Base\SmsService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class SmsRuService extends SmsService
{
    protected Client $http;

    public function __construct(readonly private array $phones, readonly private string $message)
    {
        parent::__construct();

        $this->http = new Client([
            'base_uri' => $this->url,
        ]);
    }

    public function send(): array
    {
        try {
            $response = $this->http->post('sms/send', [
                'form_params' => [
                    'api_id' => $this->apiToken,
                    'to' => implode(',', $this->phones),
                    'msg' => $this->message,
                    'json' => '1',
                    'test' => '1'
                ]
            ]);
            // TODO: Написать сюда код шестизнаковый для запроса

            $data = json_decode($response->getBody(), true);

            Log::info($data);

            return $this->parseResponse($data);
        } catch (RequestException $e) {
            Log::error($e->getMessage());
            return [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ];
        }
    }
}
