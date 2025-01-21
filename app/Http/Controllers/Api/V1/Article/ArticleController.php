<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\EmptyRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use App\Services\Article\ArticleService;
use Auth;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function get($id)
    {
        if ($id == 'all') {
            $articles = Article::paginate(3);

            return ArticleResource::collection($articles);
        }

        return response()->json(new ArticleResource(Article::find($id)));
    }

    public function update(EmptyRequest $request, $id, ArticleService $articleService): JsonResponse
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['error' => 'Курс не найден'], 404);
        }

        $data = $request->only(['title', 'description', 'content']);

        if ($articleService->isValidStatus($request->status)) {
            $data['status'] = $request->status;
        }

        if (!empty($data)) {
            $article->update($data);
        }

        $categoryIds = $request->input('category_ids', []);

        if (!empty($categoryIds)) {
            $categories = Category::whereIn('id', $categoryIds)->get();
            $article->categories()->sync($categories);
        }

        return response()->json(new ArticleResource($article), 200);
    }

    public function delete($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['error' => 'Курс не найден'], 404);
        }

        $article->delete();

        return response()->json(['message' => 'Пост удален'], 200);
    }

    public function create(ArticleRequest $request): JsonResponse
    {
        $data = $request->only(['title', 'description', 'content']);
        $data['user_id'] = Auth::id();

        $article = Article::create($data);

        // Получаем массив ID категорий из запроса
        $categoryIds = $request->input('category_ids', []);

        // Проверяем существование категорий и связываем их с постом
        $categories = Category::whereIn('id', $categoryIds)->get();
        $article->categories()->sync($categories);

        return response()->json(new ArticleResource($article), 200);
    }
}
