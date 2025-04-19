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
    public function __construct(public string $phone = '', public User|Authenticatable|null $user = null)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $smsService = new SmsRuService();

            $code = $smsService->createVerificationCode($this->phone);
            
            $smsService->send(
                [$code->phone],
                'Ваш проверочный код ' . config('app.name') . ' ' . $code->code,
                $code->ip
            );
        } catch (\Exception $e) {
            Log::channel('register_sms_create')->error('Ошибка при отправке SMS: ' . $e->getMessage(), [
                'user_id' => $this->user->id ?? null,
                'exception' => $e
            ]);
        }
    }
}
