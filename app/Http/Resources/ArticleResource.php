<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'has_certificate' => (bool) $this->has_certificate,
            'avatar' => $this->avatar,
            'owner' => new UserResource($this->user),
            'admins' => UserResource::collection($this->admins),
            'teachers' => UserResource::collection($this->teachers),
            'is_can_edit' => $this->isCanEdit(),
            'students' => UserResource::collection($this->students),
            'categories' => CategoryResource::collection($this->categories),
            'subcategories' => SubcategoryResource::collection($this->subcategories),
            'modules' => ModuleResource::collection($this->modules),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Determine if the current user can edit the article.
     */
    private function isCanEdit(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $this->user->id === $user->id ||
            $this->admins->contains('id', $user->id) ||
            $this->teachers->contains('id', $user->id);
    }
}
