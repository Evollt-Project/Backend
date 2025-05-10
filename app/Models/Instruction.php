<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    public $fillable = [
        'title',
        'logo',
        'short_description',
    ];

    public function subinstructions()
    {
        return $this->hasMany(Subinstruction::class);
    }
}
