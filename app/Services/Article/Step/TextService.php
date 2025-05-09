<?php

namespace App\Services\Article\Step;

use App\Models\Lesson;
use App\Models\TextStep;
use App\Services\Base\StepService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TextService extends StepService
{
    public static function create(Request $request): JsonResponse
    {
        $newTextStepRequest = $request->validate([
            'content' => 'required|string',
        ]);

        $newTextStep = TextStep::create($newTextStepRequest);

        $newTextStep->step()->create([
            'lesson_id' => $request->lesson_id,
            'step_id' => $newTextStep->id,
            'step_type' => TextStep::class,
            'position' => Lesson::find($request->lesson_id)->steps()->count() + 1
        ]);

        return response()->json($newTextStep);
    }
}
