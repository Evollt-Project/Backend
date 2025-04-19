<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneVerificationCode extends Model
{
    protected $table = 'phone_verification_codes';
    protected $fillable = [
        'user_id',
        'phone',
        'code',
        'ip',
        'expired_at',
    ];
}
