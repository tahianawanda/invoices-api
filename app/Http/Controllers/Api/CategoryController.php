<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Models\User;


class CategoryController extends Controller
{
    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function store(CategoryStoreRequest $request)
    {
        try {
            $category = $this->category->storeCategory($request->validated());

            return SuccessResource::make([
                'success' => true,
                'message' => 'Successfully created!',
                'data' => $category,
                'data_resource' => UserResource::class,
            ])->response()->setStatusCode(201);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'Error',
                'errors' => ['details' => $th->getMessage()]
            ])->response()->setStatusCode(501);
        }
    }
}
