<?php

namespace App\Services\Instruction;

use App\Models\Instruction;
use App\Models\Subinstruction;
use App\Services\Base\Service;

class SubinstructionService extends Service
{
    public function create(int $subinstructionIds, Instruction $instruction)
    {

    }


    public function search(string $text)
    {
        $instructions = Subinstruction::where(function ($query) use ($text) {
            $query->where('title', 'like', "%{$text}%")
                ->orWhere('short_content', 'like', "%{$text}%");
        });

        return $instructions;
    }
}
