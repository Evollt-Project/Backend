<?php

namespace App\Http\Resources;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ModuleResource extends JsonResource
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
            'opened_date' => $this->opened_date,
            'description' => $this->description,
            'lessons' => LessonResource::collection($this->lessons),
            'status' => $this->status ? $this->getModuleStatus() : false,
            'created_at' => $this->created_at
        ];
    }

    public function getModuleStatus(): bool
    {
        $user = Auth::user();
        $module = Module::findOrFail($this->id);

        if ($module) {
            $status = $user->modules()->where('module_id', $module->id)->first()->pivot->completed ?? false;
        }

        return $status;
    }
}
