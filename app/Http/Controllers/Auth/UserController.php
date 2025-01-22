<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class UserController extends Controller
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function show($id)
    {
        try {

            $user = Auth::user();

            if ($user->id != $id) {
                return ErrorResource::make([
                    'message' => 'Unauthorized access',
                    'errors' => ['details' => 'You can only access your own data.'],
                ])->response()->setStatusCode(403);
            }

            return SuccessResource::make([
                'success' => true,
                'message' => 'Successfully authenticated!',
                'data' => $user,
                'data_resource' => UserResource::class,
            ])->response()->setStatusCode(200);
        } catch (AuthenticationException $e) {
        // Captura el error de autenticación y agrega más detalles
        return ErrorResource::make([
            'message' => 'Authentication Error',
            'errors' => [
                'details' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ],
        ])->response()->setStatusCode(401);
    } catch (ModelNotFoundException $e) {
        return ErrorResource::make([
            'message' => 'User not found',
            'errors' => ['details' => $e->getMessage()],
        ])->response()->setStatusCode(404);
    } catch (\Exception $e) {
        // Captura cualquier otra excepción que no haya sido especificada
        return ErrorResource::make([
            'message' => 'Unexpected Error',
            'errors' => [
                'details' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ],
        ])->response()->setStatusCode(500);
    }
    }
}
