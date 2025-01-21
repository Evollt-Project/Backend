<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'surname' => $this->surname,
            'role' => $this->role,
            'description' => $this->description,
            'privacy' => $this->privacy == 1 ? true : false,
            'gender' => $this->gender,
            'requisites' => $this->requisite ? new RequisiteResource($this->requisite) : null,
            'job' => $this->job,
            'balance' => $this->balance,
            'mail_approve' => $this->mail_approve,
            'skills' => $this->skills,
            'avatar' => $this->avatar,
            'telegram' => $this->telegram,
            'vk' => $this->vk,
            'github' => $this->github,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth
        ];
    }
}
