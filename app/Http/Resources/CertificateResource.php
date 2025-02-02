<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "path" => $this->path,
            "user" => new UserResource($this->user),
            "article" => new ArticleResource($this->article),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
