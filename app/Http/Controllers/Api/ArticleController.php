<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return ArticleResource::make($article);
    }

    public function index()
    {
        return ArticleResource::collection(Article::all());
    }
}
