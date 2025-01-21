<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmptyRequest;
use App\Http\Requests\LessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;

class LessonController extends Controller
{
    public function get($id)
    {
        if ($id == 'all') {
            $lessons = Lesson::paginate(10);
            return LessonResource::collection($lessons);
        }

        return response()->json(new LessonResource(Lesson::find($id)));
    }

    public function update(EmptyRequest $request, $id): JsonResponse
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['error' => 'Урок не найден'], 404);
        }

        $data = $request->only(['title', 'content', 'module_id']);

        if (!empty($data)) {
            $lesson->update($data);
        }

        return response()->json(new LessonResource($lesson), 200);
    }

    public function delete($id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['error' => 'Урок не найден'], 404);
        }

        $lesson->delete();

        return response()->json(['message' => 'Урок удален'], 200);
    }

    public function create(LessonRequest $request, $id): JsonResponse
    {
        $data = $request->only(['title', 'content']);

        if (!empty($data)) {
            $data['module_id'] = $id;
            $lesson = Lesson::create($data);
        }

        return response()->json(new LessonResource($lesson), 200);
    }
}
