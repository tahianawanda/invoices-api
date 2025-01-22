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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;



class ArticleController extends Controller
{
    private Article $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::applySorts(request('sort'))->get();

        return ArticleCollection::make($articles)
            ->response()->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleStoreRequest $request)
    {
        try {

            if (Auth::check()) {
                /** @var \App\Models\User $user */
                $user = Auth::user();

                Log::info('Datos recibidos en storeArticle:', $request->all());
                Log::info('Datos recibidos en storeArticle:', $user->toArray());

                $article = $this->article->storeArticle($request->validated(), $user->id);

                return SuccessResource::make([
                    'success' => true,
                    'message' => 'The article has been successfully created!',
                    'data' => $article,
                    'data_resource' => ArticleResource::class,
                ])->response()->setStatusCode(201);
            }
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'success' => false,
                'message' => $th->getMessage(),
            ])->response()->setStatusCode(501);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return ArticleResource::make($article)
            ->response()->setStatusCode(200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
