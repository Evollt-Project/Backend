<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmptyRequest;
use App\Http\Requests\ModuleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ModuleResource;
use App\Models\Article;
use App\Models\Module;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function get($id)
    {
        if ($id == 'all') {
            $modules = Module::paginate(10);
            return ModuleResource::collection($modules);
        }

        return response()->json(new ModuleResource(Module::find($id)));
    }

    public function update(EmptyRequest $request, $id): JsonResponse
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

    public function delete($id)
    {
        $module = Module::find($id);

        if (!$module) {
            return response()->json(['error' => 'Модуль не найден'], 404);
        }

        $module->delete();

        return response()->json(['message' => 'Модуль удален'], 200);
    }

    public function create(ModuleRequest $request, $id): JsonResponse
    {
        $data = $request->only(['title', 'description', 'opened_date']);

        if (!empty($data)) {
            $data['article_id'] = $id;
            $module = Module::create($data);
        }

        return response()->json(new ModuleResource($module), 200);
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
                'error' => 'Unable to complete the module. Please try again.'
            ], 400);
        }

        // Возвращение обновленного модуля с ресурсом
        return new ModuleResource($module);
    }
}
