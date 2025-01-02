<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can sort articles by title asc', function () {
    Article::factory()->create(['title' => 'C Title']);
    Article::factory()->create(['title' => 'A Title']);
    Article::factory()->create(['title' => 'B Title']);

    $url = route('api.articles.index', ['sort' => 'title']);

    $this->getJson($url)->assertSeeInOrder([
        'A Title',
        'B Title',
        'C Title',
    ]);
});

it('can sort articles by title desc', function () {
    Article::factory()->create(['title' => 'C Title']);
    Article::factory()->create(['title' => 'A Title']);
    Article::factory()->create(['title' => 'B Title']);

    $url = route('api.articles.index', ['sort' => '-title']);

    $this->getJson($url)->assertSeeInOrder([
        'C Title',
        'B Title',
        'A Title',
    ]);
});


it('can sort articles by title and content', function () {
    // $this->withoutExceptionHandling();

    Article::factory()->create([
        'title' => 'C Title',
        'content' => 'A content'
    ]);
    Article::factory()->create([
        'title' => 'A Title',
        'content' => 'B content'
    ]);
    Article::factory()->create([
        'title' => 'B Title',
        'content' => 'C content'
    ]);

    // \DB::listen(function($db) {
    //     dump($db->sql);
    // });

    $url = route('api.articles.index', ['sort' => 'title,content']); //Concatenar la ,

    $this->getJson($url)->assertSeeInOrder([
        'A Title',
        'B Title',
        'C Title',      
    ]);
});

it('cannot sort articles by unknown fields', function () {
    // $this->withoutExceptionHandling();

    Article::factory()->create([
        'title' => 'C Title',
        'content' => 'A content'
    ]);
    Article::factory()->create([
        'title' => 'A Title',
        'content' => 'B content'
    ]);
    Article::factory()->create([
        'title' => 'B Title',
        'content' => 'C content'
    ]);

    $url = route('api.articles.index', ['sort' => 'x']); //Concatenar la ,

    $this->getJson($url)->assertStatus(400);
});
