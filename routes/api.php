<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Log;

Route::post('debug', function (Request $request) {
    Log::info('Request headers:', $request->headers->all());
    Log::info('Request body:', $request->all());

    return response()->json(['message' => 'Debug route executed. Check logs.']);
});


Route::get('articles', [ArticleController::class, 'index'])->name('api.articles.index');
Route::get('articles/{article}', [ArticleController::class, 'show'])->name('api.articles.show');
Route::post('articles', [ArticleController::class, 'store'])->name('api.articles.store');

Route::post('category', [CategoryController::class, 'store'])->name('category.store');