<?php

namespace App\Services\Instruction;

use App\Models\Instruction;
use App\Services\Base\Service;

class InstructionService extends Service
{
    public function search(string $text)
    {
        $instructions = Instruction::where(function ($query) use ($text) {
            $query->where('title', 'like', "%{$text}%")
                ->orWhere('short_description', 'like', "%{$text}%");
        })->paginate(10);

        return $instructions;
    }
}
