<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'photo' => $this->photo,
            'color' => $this->color,
            'articles_count' => count($this->articles),
            'description' => $this->description,
            'catalog' => $this->catalog,
            'subcategories' => SubcategoryResource::collection($this->subcategories)
        ];
    }
}
