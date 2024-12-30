<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;

uses(RefreshDatabase::class);

it('retrieves an article by its ID and displays its title', function () {
    $this->withoutExceptionHandling();

    $article = Article::factory()->create();

    $response = $this->getJson(route('api.articles.show', $article));

    $response->assertExactJson([
        'data' => [
            'type' => 'articles',
            'id' => (string) $article->getRouteKey(),
            'attributes' => [
                'title' => $article->title,
                'slug' => $article->slug,
                'content' => $article->content,
            ],
            'links' => [
                'self' => route('api.articles.show', $article)
            ]
        ]
    ]);
});

it('retrieves all articles and displays their titles', function () {
    $this->withoutExceptionHandling();

    $articles = Article::factory()->count(3)->create();

    $response = $this->getJson(route('api.articles.index'));

    $response->assertExactJson([
        'data' => [
            [
                'type' => 'articles',
                'id' => (string) $articles[0]->getRouteKey(),
                'attributes' => [
                    'title' => $articles[0]->title,
                    'slug' => $articles[0]->slug,
                    'content' => $articles[0]->content,
                ],
                'links' => [
                    'self' => route('api.articles.show', $articles[0])
                ]
            ],
            [
                'type' => 'articles',
                'id' => (string) $articles[1]->getRouteKey(),
                'attributes' => [
                    'title' => $articles[1]->title,
                    'slug' => $articles[1]->slug,
                    'content' => $articles[1]->content,
                ],
                'links' => [
                    'self' => route('api.articles.show', $articles[1])
                ]
            ],
            [
                'type' => 'articles',
                'id' => (string) $articles[2]->getRouteKey(),
                'attributes' => [
                    'title' => $articles[2]->title,
                    'slug' => $articles[2]->slug,
                    'content' => $articles[2]->content,
                ],
                'links' => [
                    'self' => route('api.articles.show', $articles[2])
                ]
            ]
        ],
        'links' => [
            'self' => route('api.articles.index')
        ],
        'meta' => [
            'articles_count' => 3
        ]
    ]);
});
