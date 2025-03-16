<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleRequest;
use App\Http\Resources\ModuleResource;
use App\Models\Article;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $article = Article::find($request->article_id);
        if (!$article)
            return response()->json([
                'error' => "Такой курс не найден"
            ]);
        return response()->json(ModuleResource::collection($article->modules));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ModuleRequest $request)
    {
        $data = $request->only(['title', 'description', 'opened_date', 'article_id']);

        if(!isset($data['description'])) {
            $data['description'] = '';
        }

        if (!empty($data)) {
            $module = Module::create($data);
        }

        return response()->json(new ModuleResource($module), 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(new ModuleResource(Module::find($id)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['error' => 'Модуль не найден'], 404);
        }

        $data = $request->only(['title', 'description', 'opened_date']);

        if (!empty($data)) {
            $module->update($data);
        }

        return response()->json(new ModuleResource($module), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['error' => 'Модуль не найден'], 404);
        }

        $module->delete();

        return response()->json(['message' => 'Модуль удален'], 200);
    }

    public function complete($id)
    {
        $user = Auth::user();

        // Проверка, существует ли модуль
        $module = Module::findOrFail($id);

        // Добавление записи в таблицу module_users с флагом completed = true
        try {
            $user->modules()->syncWithoutDetaching([$module->id => ['completed' => true]]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Нельзя законить модуль, пожалуйста, попробуйте снова'
            ], 400);
        }

        return new ModuleResource($module);
    }
}
