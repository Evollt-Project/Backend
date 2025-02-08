<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CertificateType extends Model
{
    use HasFactory;
    public $fillable = [
        'path',
        'title',
        'state',
        'user_id',
        'title_position',
        'date_position',
        'logo_position'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
