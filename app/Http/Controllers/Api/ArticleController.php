<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Models\Article;
use Illuminate\Support\Facades\Log;


class ArticleController extends Controller
{
    private Article $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);

        return ArticleResource::make($article);
    }

    public function index()
    {
        $articles = Article::applySorts(request('sort'))->get();

        return ArticleCollection::make($articles);
    }

    public function store(ArticleStoreRequest $request)
    {
        try {
            Log::info('Datos recibidos en storeArticle:', $request->all());

            $article = $this->article->storeArticle($request->validated());

            return SuccessResource::make([
                'success' => true,
                'message' => 'The article has been successfully created!',
                'data' => $article,
                'data_resource' => ArticleResource::class,
            ]);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
