<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Enums\RoleEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Jobs\Article\CreateJob as ArticleCreateJob;
use App\Models\Article;
use App\Models\ArticleAdmin;
use App\Models\ArticleTeacher;
use App\Models\Category;
use App\Models\Subcategory;
use App\Services\Article\ArticleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'big', 'online']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ArticleService $articleService)
    {
        $perPage = $request->query('per_page') ?? 10;
        $articles = $articleService->get($request);

        return ArticleResource::collection($articles->paginate($perPage));
    }

    public function search(Request $request, ArticleService $articleService)
    {
        $perPage = $request->query('per_page') ?? 10;
        $articles = $articleService->get($request);

        return ArticleResource::collection($articles->paginate($perPage));
    }

    public function teaching(Request $request, ArticleService $articleService)
    {
        $perPage = (int)$request->query('per_page', 10);
        $user = Auth::user();

        $query = $user->teaching()->getQuery();

        $filtered = $articleService->applyFilters($query, $request->all());

        return ArticleResource::collection($filtered->paginate($perPage));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $user = Auth::user();
        $data = $request->only(['title']);
        $data['user_id'] = $user->id;
        $data['level_id'] = 1;
        $data['language_id'] = 1;

        $article = Article::create($data);

        // Получаем массив ID категорий из запроса
        $categoryIds = $request->input('category_ids', []);
        $subcategoryIds = $request->input('subcategory_ids', []);

        // Проверяем существование категорий и связываем их с постом
        $categories = Category::whereIn('id', $categoryIds)->get();
        $article->categories()->sync($categories);

        $subcategories = Subcategory::whereIn('id', $subcategoryIds)->get();
        $article->categories()->sync($subcategories);

        ArticleTeacher::create([
            'article_id' => $article->id,
            'user_id' => $user->id,
        ]);

        ArticleAdmin::create([
            'article_id' => $article->id,
            'user_id' => $user->id,
        ]);

        if ($user['role'] < RoleEnums::TEACHER->value) {
            $user->update(['role' => RoleEnums::TEACHER->value]);
        }

        ArticleCreateJob::dispatch($user, $article);

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
        // TODO: Доделать обновление курса (используются другие поля)
        $article = Article::find($id);
        $user = Auth::user();

        if (!$article) {
            return response()->json(['error' => 'Курс не найден'], 404);
        }

        $data = $request->only(
            [
                'title',
                'avatar',
                'short_content',
                'what_learn_content',
                'about_content',
                'for_who_content',
                'start_content',
                'how_learn_content',
                'what_give_content',
                'recommended_load',
                'level_id',
                'language_id',
                'user_id'
            ]
        );

        if (
            $request->has('status') &&
            $articleService->isValidStatus($request->status) &&
            $user->role == RoleEnums::ADMIN
        ) {
            $data['status'] = $request->status;
        } else {
            return response()->json([
                'message' => 'Статус имеет невалидное значение, либо пользователь не имеет права менять статус курса'
            ]);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                $path = $file->store('articles', 'public');
                $article->avatar = $path;
            }
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
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'errors' => 'Не удалось загрузить онлайн курсы'
            ], 500);
        }
    }

    public function big()
    {
        try {
            $take = 12;
            $bigArticles = Article::select('articles.*')
                ->orderBy('time', 'desc')
                ->take($take)
                ->get();

            return response()->json([
                [
                    'tab' => null,
                    'articles' => ArticleResource::collection($bigArticles)
                ],
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'errors' => 'Не удалось загрузить большие курсы'
            ], 500);
        }
    }
}
