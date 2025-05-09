<?php

use App\Http\Controllers\Api\V1\Admin\CatalogController;
use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Article\ArticleController;
use App\Http\Controllers\Api\V1\Article\CertificateController;
use App\Http\Controllers\Api\V1\Article\CertificateTypeController;
use App\Http\Controllers\Api\V1\Article\LessonController;
use App\Http\Controllers\Api\V1\Article\ModuleController;
use App\Http\Controllers\Api\V1\Article\Step\StepController;
use App\Http\Controllers\Api\V1\GeneralController;
use App\Http\Controllers\Api\V1\Instruction\InstructionController;
use App\Http\Controllers\Api\V1\Instruction\SubinstructionController;
use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('enums', [GeneralController::class, 'enums']);

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('step/code', 'code');
    Route::get('/approve/email/{id}/{hash}', 'approveEmail')->middleware(['signed'])->name('verification.verify');
    Route::get('/email/resend', 'emailResend')->middleware(['auth:sanctum']);
    Route::get('step/code-check', 'codeCheck');
    Route::get('step/email-check-exists', 'emailCheckExists');
    Route::get('logout', 'logout')->middleware(['auth:sanctum']);
});

Route::prefix('user')->middleware(['auth:sanctum'])->controller(UserController::class)->group(function () {
    Route::get('get', 'get');
    Route::get('skills', 'skills');
    Route::put('update', 'update');
    Route::post('update/password', 'changePassword');
});
Route::get('user/get/{id}', [UserController::class, 'getById']);

Route::resource('article', ArticleController::class);
Route::prefix('articles')->controller(ArticleController::class)->group(function () {
    Route::get('online', 'online');
    Route::get('big', 'big');
    Route::get('teaching', 'teaching');
});

Route::put('lesson/reorder', [LessonController::class, 'reorder'])->middleware('auth:sanctum');
Route::resource('lesson', LessonController::class)->middleware('auth:sanctum');
Route::resource('step', StepController::class)->middleware('auth:sanctum');

Route::resource('module', ModuleController::class)->middleware('auth:sanctum');
Route::resource('category', CategoryController::class);
Route::resource('category', CategoryController::class);
Route::resource('catalog', CatalogController::class);
Route::resource('certificate', CertificateController::class)->middleware(['auth:sanctum']);
Route::resource('certificate_type', CertificateTypeController::class)->middleware(['auth:sanctum']);

// Инструкции
Route::resource('instruction', InstructionController::class);

// Подинструкция
Route::resource('subinstruction', SubinstructionController::class);
