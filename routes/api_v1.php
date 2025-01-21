<?php

use App\Http\Controllers\Api\V1\Admin\CatalogController;
use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Article\ArticleController;
use App\Http\Controllers\Api\V1\Article\LessonController;
use App\Http\Controllers\Api\V1\Article\ModuleController;
use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('enums', [UserController::class, 'enums']);

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('logout', 'logout')->middleware(['auth:sanctum']);
});

Route::prefix('user')->middleware(['auth:sanctum'])->controller(UserController::class)->group(function () {
    Route::get('get', 'get');
    Route::get('skills', 'skills');
    Route::put('update', 'update');
    Route::post('update/password', 'changePassword');
});

Route::prefix('article')->middleware(['auth:sanctum'])->controller(ArticleController::class)->group(function () {
    Route::get('get/{id}', 'get');
    Route::post('create', 'create');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'delete');
});

Route::prefix('module')->middleware(['auth:sanctum'])->controller(ModuleController::class)->group(function () {
    Route::get('get/{id}', 'get');
    Route::get('complete/{id}', 'complete');
    Route::post('create/{id}', 'create');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'delete');
});

Route::prefix('lesson')->middleware(['auth:sanctum'])->controller(LessonController::class)->group(function () {
    Route::get('get/{id}', 'get');
    Route::post('create/{id}', 'create');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'delete');
});

Route::resource('category', CategoryController::class);
Route::resource('catalog', CatalogController::class);

// TODO: Добавить CRUD уроков

// Route::get('category/get/{id}', [CategoryController::class, 'get']);
// Route::prefix('category')->middleware(['auth:sanctum'])->controller(CategoryController::class)->group(function () {
//     Route::post('/create', 'create');
//     Route::delete('/delete/{id}', 'delete');
// });

// Route::get('post/get/{id}', [PostController::class, 'get']);
// Route::prefix('post')->middleware(['auth:sanctum'])->controller(PostController::class)->group(function () {
//     Route::post('/create', 'create');
//     Route::put('/update/{id}', 'update');
//     Route::get('/my', 'my');
//     Route::delete('/delete/{id}', 'delete');
// });
