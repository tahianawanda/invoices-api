<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;




Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/user/{id}', [UserController::class, 'show'])
    ->middleware('auth')
    ->name('user');

Route::post('debug', function (Request $request) {
    Log::info('Request headers:', $request->headers->all());
    Log::info('Request body:', $request->all());

    return response()->json(['message' => 'Debug route executed. Check logs.']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('articles', [ArticleController::class, 'index'])->name('api.articles.index');
    Route::get('articles/{article}', [ArticleController::class, 'show'])->name('api.articles.show');
    Route::post('articles', [ArticleController::class, 'store'])->name('api.articles.store');
});
