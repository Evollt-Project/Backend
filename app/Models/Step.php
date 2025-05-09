<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = ['lesson_id', 'step_id', 'step_type', 'position'];

    public function stepable()
    {
        return $this->morphTo();
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
