<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;

Route::get('articles', [ArticleController::class, 'index'])->name('api.articles.index');
Route::get('articles/{article}', [ArticleController::class, 'show'])->name('api.articles.show');
Route::post('articles', [ArticleController::class, 'store'])->name('api.articles.store');

