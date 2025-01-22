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
use Illuminate\Support\Facades\Log;


class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {

            Log::info('Datos recibidos en storeUser:', $request->all());

            $request->authenticate();

            $request->session()->regenerate();

            $user = Auth::user();

            return SuccessResource::make([
                'success' => true,
                'message' => 'Successful login!',
                'data' => $user,
                'data_resource' => UserResource::class,
            ])->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'Failed to authenticate',
                'errors' => ['details' => $th->getMessage()],
            ])->response()->setStatusCode(401);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        try {
            log::info('User auth: ', $request->all());
            
            $user = Auth::user();

            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return SuccessResource::make([
                'success' => true,
                'message' => 'Successfully disconnected!',
                'data' => $user,
                'data_resource' => UserResource::class,
            ])->response()->setStatusCode(200);
        } catch (\Throwable $th) {
            return ErrorResource::make([
                'message' => 'Failed to logout',
                'errors' => ['details' => $th->getMessage()],
            ])->response()->setStatusCode(401);
        }
    }
}
