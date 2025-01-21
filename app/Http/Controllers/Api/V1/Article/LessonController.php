<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lesson::paginate(10);
        return LessonResource::collection($lessons);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LessonRequest $request)
    {
        $data = $request->only(['title', 'content', 'module_id']);

        if (!empty($data)) {
            $lesson = Lesson::create($data);
        }

        return response()->json(new LessonResource($lesson), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(new LessonResource(Lesson::find($id)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['error' => 'Урок не найден'], 404);
        }

        $lesson->delete();

        return response()->json(['message' => 'Урок удален'], 200);
    }
}
