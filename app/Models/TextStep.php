<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextStep extends Model
{
    public $fillable = [
        'content',
    ];

    public function step()
    {
        return $this->morphOne(Step::class, 'stepable');
    }
}
