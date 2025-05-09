<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'description' => $this->description,
            'content' => $this->content,
            'status' => $this->status,
            'price' => $this->price,
            'time' => $this->time,
            'level' => new LevelResource($this->level),
            'language' => new LanguageResource($this->language),
            'has_certificate' => (bool)$this->has_certificate,
            'avatar' => $this->avatar,
            'owner' => new UserResource($this->user),
            'admins' => UserResource::collection($this->admins),
            'teachers' => UserResource::collection($this->teachers),
            'students' => UserResource::collection($this->students),
            'categories' => CategoryResource::collection($this->categories),
            'subcategories' => SubcategoryResource::collection($this->subcategories),
            'modules' => ModuleResource::collection($this->modules),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
