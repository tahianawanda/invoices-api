<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function storeCategory(array $request)
    {
        $category = new Category([
            'name' => $request['name'],
            'slug' => $request['slug']
        ]);

        $category->save();

        return $category;
    }

    public function search($id)
    {
        try {
            $category = $this->findOrFail($id);

            return $category;
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException;
        }
    }

    public function showCategory($id)
    {
        $category = $this->search($id);

        return $category;
    }

    public function indexCategory()
    {
        return $this->all();
    }

    public function updateCategory(array $request, string $id)
    {
        $category = $this->search($id);

        $category->update($request);

        return $category;
    }

    public function destroyCategory(string $id)
    {
        $category = $this->search($id);

        $category->delete();

        return $category;
    }
}
