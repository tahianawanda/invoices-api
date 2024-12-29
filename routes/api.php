<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;


Route::get('articles/{article}', [ArticleController::class, 'show'])->name('api.articles.show');

Route::get('articles', [ArticleController::class, 'index'])->name('api.articles.index');