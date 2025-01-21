<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisite extends Model
{
    public $fillable = [
        'nalog_status',
        'user_id',
        'passport',
        'legal_address',
        'date_of_birth',
        'inn',
        'fio',
        'bik',
        'bank',
        'payment_account'
    ];
}
