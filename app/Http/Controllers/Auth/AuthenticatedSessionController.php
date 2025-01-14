<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            $user = Auth::user();

            return SuccessResource::make([
                'success' => true,
                'message' => 'Successful login!',
                'data' => $user,
                'data_resource' => UserResource::class,
            ], 200);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'Failed to authenticate',
                'errors' => ['details' => $th->getMessage()],
            ], 401);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        try {
            $user = Auth::user();

            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return SuccessResource::make([
                'success' => true,
                'data' => $user,
                'data_resource' => UserResource::class,
            ]);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'Failed to logout',
                'errors' => ['details' => $th->getMessage()],
            ]);
        }
    }
}
