<?php

namespace App\Jobs\User;

use App\Models\User;
use App\Services\Sms\SmsRuService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendPhoneVerificationCodeJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User|Authenticatable $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $smsService = new SmsRuService($this->user);

        $code = $smsService->createVerificationCode($this->user);

        Log::info($code->ip);

        $smsService->send([$code->phone],
            'Ваш проверочный код ' . config('app.name') . ' ' . $code->code,
            $code->ip);
    }
}
