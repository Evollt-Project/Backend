<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'logo' => $this->logo,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'subinstructions' => SubinstructionResource::collection($this->subinstructions)
        ];
    }
}
