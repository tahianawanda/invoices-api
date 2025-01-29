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
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Obtener todas las categorías",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorías",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CategoryResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResource")
     *     )
     * )
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
            ])->response()->setStatusCode(500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/category",
     *     summary="Create a new category",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "slug"},
     *             @OA\Property(property="name", type="string", example="Plants"),
     *             @OA\Property(property="slug", type="string", example="plants")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error occurred.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResource")
     *     )
     * )
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
            ])->response()->setStatusCode(400);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'An unexpected error occurred.',
                'errors' => ['details' => $th->getMessage()]
            ])->response()->setStatusCode(500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/category/{id}",
     *     summary="Get a single category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category details",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResource")
     *     )
     * )
     */
    public function show(string $id)
    {
        try {
            $category = $this->category->showCategory($id);

            return CategoryResource::make($category)
                ->response()
                ->setStatusCode(200);
        } catch (ModelNotFoundException $e) {
            return ErrorResource::make([
                'message' => 'Category not found',
                'errors' => ['details' => $e->getMessage()]
            ])->response()->setStatusCode(404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/category/{id}",
     *     summary="Update a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "slug"},
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="slug", type="string", example="updated-name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResource")
     *     )
     * )
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
                'message' => 'Category not found',
                'errors' => ['details' => $e->getMessage()]
            ])->response()->setStatusCode(404);
        } catch (ValidationException $e) {
            return ErrorResource::make([
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ])->response()->setStatusCode(400);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'An unexpected error occurred.',
                'errors' => ['details' => $th->getMessage()]
            ])->response()->setStatusCode(500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/category/{id}",
     *     summary="Delete a category",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An unexpected error occurred.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResource")
     *     )
     * )
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
                'message' => 'Category not found.',
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
