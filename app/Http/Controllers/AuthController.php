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
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Create new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:30', 'unique:users'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'email.required' => 'Поле email не заполнено.',
            'email.unique' => "Пользователь {$request['email']} уже есть.",
            'email.max' => 'Поле email должно быть не длиньше :max символов.',
            'name.required' => 'Заполните ваше имя.',
            'password.required' => 'Заполните поле пароль.',
            'password.min' => 'Минимум :min символов.',
            'password.confirmed' => 'Проверьте подтверждение пароля.',
        ]);

        if ( $validator->fails() ) {
            return response()->json(['message' => 'Проверьте ваши данные!',
                'error' => $validator->errors()
            ], 401);
        }

        try {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user->save();

            return $this->login(201);

        } catch (Throwable $e) {
            return response()->json([
                'message'=>'Не удалось сохранить нового пользователя.',
                'error'=>$e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param int $status_code Optional, to pass status code 201 to login() method when triggered as aftereffect of register()
     * @return JsonResponse
     */
    public function login($status_code = 200)
    {
        $validator = Validator::make( request()->all(), [
            'email' => ['required', 'string', 'email', 'max:20'],
            'password' => ['required', Password::defaults()],
        ], [
            'email.required' => 'Поле email не заполнено.',
            'email.max' => 'Поле email должно быть не длиньше :max символов.',
            'password.required' => 'Заполните поле пароль.',
            'password.min' => 'Минимум :min символов.',
        ]);
        if ( $validator->fails() ) {
            return response()->json([
                'message' => 'Проверьте ваши данные!',
                'error' => $validator->errors()
            ], 401);
        }

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token, $status_code);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token, $status_code = 200)
    {

        $user = User::whereEmail( request('email') )->first();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user,
        ], $status_code);
    }
}
