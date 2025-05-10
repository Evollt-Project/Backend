<?php

namespace App\Services\Instruction;

use App\Models\Instruction;
use App\Models\Subinstruction;
use App\Services\Base\Service;
use Illuminate\Http\Request;

class SubinstructionService extends Service
{
    public function filter(array $data)
    {
        $subinstructions = $this->search($data['search'] ?? '');

        if (isset($data['instruction_id'])) {
            $subinstructions = $subinstructions->where('instruction_id', $data['instruction_id']);
        }

        return $subinstructions;
    }

    public function search(string $text)
    {
        $subinstructions = Subinstruction::where(function ($query) use ($text) {
            $query->where('title', 'like', "%{$text}%")
                ->orWhere('short_description', 'like', "%{$text}%");
        });

        return $subinstructions;
    }
}
