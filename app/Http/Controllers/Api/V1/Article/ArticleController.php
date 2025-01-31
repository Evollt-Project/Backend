<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use App\Services\Article\ArticleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::paginate(10);

        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['error' => 'Курс не найден'], 404);
        }
        return response()->json(new ArticleResource($article));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, ArticleService $articleService)
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['error' => 'Курс не найден'], 404);
        }

        $article->delete();

        return response()->json(['message' => 'Пост удален'], 200);
    }

    public function online()
    {
        try {
            $take = 12;
            $trendArticles = Article::select('articles.*')
                ->withCount('students as students_count')
                ->orderBy('students_count', 'desc')
                ->take($take)
                ->get();

            $newArticles = Article::orderBy('created_at', 'desc')
                ->take($take)
                ->get();

            return response()->json([
                [
                    'tab' => "В тренде",
                    'articles' => ArticleResource::collection($trendArticles)
                ],
                [
                    'tab' => "Новые",
                    'articles' => ArticleResource::collection($newArticles)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => 'Не удалось загрузить онлайн курсы'
            ], 500);
        }
    }
}
