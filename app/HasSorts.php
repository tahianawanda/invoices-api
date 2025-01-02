<?php

namespace App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Models\Article;

trait HasSorts
{
    public function scopeApplySorts(Builder $query, $sort)
    {
        if( ! property_exists(Article::class, 'correctSorts'))
        {
            abort(500, 'Please set the public property $correctSorts inside '.get_class($this));
        }
        
        if (is_null($sort))
        {
            return;
        }

        $sortFields = Str::of($sort)->explode(',');

        foreach ($sortFields as $sortField) {
            if (!collect($this->correctSorts)->contains($sortField)) {
                abort(400, "Invalid Query Parameter, {$sortField} is not allowed.");
            }
            $direction = 'asc';

            if (Str::of($sortField)->startsWith('-')) {
                $direction = 'desc';
                $sortField = Str::of($sortField)->substr(1);
            }

            $query->orderBy($sortField, $direction);
        }
    }
}


