<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmedPhone extends Model
{
    protected $fillable = [
        'phone',
        'ip',
        'user_id'
    ];
}
