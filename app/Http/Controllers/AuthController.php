<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Throwable;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * Create new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = User::validate_data('register');

        if (isset($validator['failed'])) {
            return $validator['validation_failed_json_response'];
        }

        User::query()->create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->login(201);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @param int $status_code For automatic login after registering, to return status code 201.
     * @return JsonResponse
     */
    public function login(int $status_code = 200): JsonResponse
    {
        $validator = User::validate_data();

        if (isset($validator['failed'])) {
            return $validator['validation_failed_json_response'];
        }

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Не авторизованы!'], 401);
        }

        return $this->respondWithToken($token, $status_code);
    }


    /**
     * Get the token array structure.
     *
     * @param string $token
     * @param int $status_code
     * @return JsonResponse
     */
    protected function respondWithToken(string $token, int $status_code = 200): JsonResponse
    {

        $user = User::whereEmail(request('email'))->first();

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => $user,
        ], $status_code);
    }


    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
