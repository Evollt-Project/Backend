<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CatalogResource;
use App\Models\Catalog;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(CatalogResource::collection(Catalog::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request Данные запроса
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Получаем данные для создания каталога
        $data = $request->only(['title']);

        // Создаем новый каталог
        $catalog = Catalog::create($data);

        // Получаем массив ID категорий из запроса
        $categoryIds = $request->input('category_ids', []);

        // Удаляем все текущие категории, связанные с каталогом
        // Это удалит связь с каталогом, но не сами категории
        $catalog->categories()->update(['catalog_id' => null]);

        // Привязываем существующие категории к каталогу
        Category::whereIn('id', $categoryIds)->update(['catalog_id' => $catalog->id]);

        // Возвращаем JSON-ответ с ресурсом каталога
        return response()->json(new CatalogResource($catalog), 200);
    }

    /**
     * Отображает информацию о категории по её идентификатору.
     *
     * @param string $id Идентификатор категории.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $catalog = Catalog::find($id);
        if (!$catalog) {
            response()->json([
                'error' => "Каталог с таким id не был найден"
            ]);
        }
        return response()->json(new CatalogResource($catalog));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request Данные запроса
     * @param string $id Идентификатор категории
     *
     * @return JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $catalog = Catalog::find($id);

        if (!$catalog) {
            return response()->json(['error' => 'Курс не найден'], 404);
        }

        $data = $request->only(['title']);

        if (!empty($data)) {
            $catalog->update($data);
        }

        $categoryIds = $request->input('category_ids', []);
        $catalog->categories()->update(['catalog_id' => null]);

        if (!empty($categoryIds)) {
            // Привязываем существующие категории к каталогу
            Category::whereIn('id', $categoryIds)->update(['catalog_id' => $catalog->id]);
        }

        return response()->json(new CatalogResource($catalog), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id Идентификатор категории
     *
     * @return JsonResponse
     */
    public function destroy(string $id)
    {
        $catalog = Catalog::find($id);

        if (!$catalog) {
            return response()->json(['error' => 'Каталог не найден'], 404);
        }

        $catalog->delete();

        return response()->json(['message' => 'Каталог удален'], 200);
    }
}
