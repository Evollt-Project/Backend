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
            'owner' => new UserResource($this->user),
            'admins' => UserResource::collection($this->admins),
            'teachers' => UserResource::collection($this->teachers),
            'students' => UserResource::collection($this->students),
            'categories' => $this->categories,
            'modules' => ModuleResource::collection($this->modules)
        ];
    }
}
