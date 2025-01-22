<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Log;


/**
 * 
 *  Ruta para depuración de solicitudes. 
 * Registra los encabezados y el cuerpo de la solicitud en los logs.
 * 
 */
Route::post('debug', function (Request $request) {
    // Registramos los encabezados de la solicitud en los logs.
    Log::info('Request headers:', $request->headers->all());
    
    // Registramos el cuerpo de la solicitud en los logs.
    Log::info('Request body:', $request->all());

    // Respuesta con un mensaje que indica que la ruta fue ejecutada correctamente.
    return response()->json(['message' => 'Debug route executed. Check logs.']);
});


/**
 * 
 * Rutas para gestionar artículos
 * 
 */
Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::post('articles', [ArticleController::class, 'store'])->name('articles.store');
Route::delete('articles', [ArticleController::class, 'destroy'])->name('articles.destroy');
Route::put('articles', [ArticleController::class, 'update'])->name('articles.update');


/**
 * 
 * Rutas para gestionar categorías
 * 
 */
Route::post('category', [CategoryController::class, 'store'])->name('category.store');
Route::get('category', [CategoryController::class, 'index'])->name('category.index');
Route::get('category/{id}', [CategoryController::class, 'show'])->name('category.show');
Route::delete('category', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::put('category', [CategoryController::class, 'update'])->name('category.update');
