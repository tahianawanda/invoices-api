<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $categories = $this->category->indexCategory();

            Log::info('List Categories', $categories->toArray());

            return CategoryCollection::make($categories);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'No categories found.',
                'errors' => ['details' => $th->getMessage()]
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        try {
            $category = $this->category->storeCategory($request->validated());

            return SuccessResource::make([
                'success' => true,
                'message' => 'Successfully created!',
                'data' => CategoryResource::make($category),
            ])->response()->setStatusCode(201);
        } catch (ValidationException $e) {
            return ErrorResource::make([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ])->response()->setStatusCode(422);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'An unexpected error occurred.',
                'errors' => ['details' => $th->getMessage()]
            ])->response()->setStatusCode(500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = $this->category->showCategory($id);

            return CategoryResource::make($category);
        } catch (ModelNotFoundException $e) {
            return ErrorResource::make([
                'message' => 'Resource not found.',
                'errors' => ['details' => $e->getMessage()]
            ])->response()->setStatusCode(404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $id)
    {
        try {
            Log::info('Request data: ', $request->all());

            $category = $this->category->updateCategory($request->validated(), $id);

            return SuccessResource::make([
                'success' => true,
                'message' => 'Resource updated successfully!',
                'data' => CategoryResource::make($category),
            ])->response()->setStatusCode(200);
        } catch (ModelNotFoundException $e) {
            return ErrorResource::make([
                'message' => 'Resource not found.',
                'errors' => ['details' => $e->getMessage()]
            ])->response()->setStatusCode(404);
        } catch (ValidationException $e) {
            return ErrorResource::make([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ])->response()->setStatusCode(422);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'An unexpected error occurred.',
                'errors' => ['details' => $th->getMessage()]
            ])->response()->setStatusCode(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->category->destroyCategory($id);

            return SuccessResource::make([
                'success' => true,
                'message' => 'Resource deleted successfully!',
            ])->response()->setStatusCode(200);
        } catch (ModelNotFoundException $e) {
            return ErrorResource::make([
                'message' => 'Resource not found.',
                'errors' => ['details' => $e->getMessage()]
            ])->response()->setStatusCode(404);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'An unexpected error occurred.',
                'errors' => ['details' => $th->getMessage()]
            ])->response()->setStatusCode(500);
        }
    }
}
