<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateTypeResource extends JsonResource
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
            "preview_image" => $this->preview_image,
            "title" => $this->title,
            "owner" => new UserResource($this->user),
            "positions" => [
                json_decode($this->title_position),
                json_decode($this->date_position),
                json_decode($this->logo_position)
            ],
            "state" => $this->state,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
