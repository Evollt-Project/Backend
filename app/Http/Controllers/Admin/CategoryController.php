<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(CategoryResource::collection(Category::all()));
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
        $data = $request->only(['title', 'description']);
        $data['catalog_id'] = $request->catalog_id;

        $category = Category::create($data);

        return response()->json(new CategoryResource($category), 200);
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
        return response()->json(new CategoryResource(Category::find($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request Данные запроса
     * @param string $id Идентификатор категории
     *
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Категория не найдена'], 404);
        }

        $data = $request->only(['title', 'description', 'catalog_id']);

        if (!empty($data)) {
            $category->update($data);
        }

        return response()->json(new CategoryResource($category), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id Идентификатор категории
     *
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Категория не найдена'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Категория удалена'], 200);

    }
}
