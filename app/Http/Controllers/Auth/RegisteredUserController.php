<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;





class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            Log::info('Datos recibidos en storeUser:', $request->all());

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->string('password')),
            ]);

            event(new Registered($user));

            Auth::login($user);

            return SuccessResource::make([
                'success' => true,
                'message' => 'Successfully registered!',
                'data' => $user,
                'data_resource' => UserResource::class,
            ])->response()->setStatusCode(200);
        } catch (ValidationException $e) {
            Log::warning('Errores de validación:', $e->errors());
            return ErrorResource::make([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ])->response()->setStatusCode(422);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                Log::warning('Conflicto de datos únicos:', ['exception' => $e]);
                return ErrorResource::make([
                    'message' => 'Conflict: Duplicate entry',
                    'errors' => ['details' => 'The email is already in use.'],
                ])->response()->setStatusCode(409);
            }
            throw $e;
        } catch (\PDOException $e) {
            Log::error('Error de conexión a la base de datos:', ['exception' => $e]);
            return ErrorResource::make([
                'message' => 'Database connection error',
                'errors' => ['details' => $e->getMessage()],
            ])->response()->setStatusCode(503);
        } catch (HttpException $e) {
            Log::warning('Demasiadas solicitudes de registro:', ['exception' => $e]);
            return ErrorResource::make([
                'message' => 'Too many requests',
                'errors' => ['details' => 'You have exceeded the number of allowed registration attempts. Try again later.'],
            ])->response()->setStatusCode(429);
        } catch (\Throwable $th) {
            Log::error('Error general al registrar usuario:', ['exception' => $th]);
            return ErrorResource::make([
                'message' => 'Failed to register',
                'errors' => ['details' => $th->getMessage()],
            ])->response()->setStatusCode(500);
        }
    }
}
