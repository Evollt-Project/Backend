<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateType extends Model
{
    use HasFactory;
    public $fillable = [
        'path',
        'state',
        'user_id',
        'title_position',
        'date_position',
        'logo_position'
    ];
}
