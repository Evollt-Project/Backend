<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subinstruction extends Model
{
    protected $fillable = [
        'title',
        'logo',
        'short_content',
        'content',
        'instruction_id'
    ];

    public function instruction()
    {
        return $this->belongsTo(Instruction::class);
    }
}
