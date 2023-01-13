<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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
        $this->middleware('JWT', ['except' => ['login', 'signup', 'index']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email or password is wrong', 'credentials' => print_r($credentials)], 401);
        }

        return $this->respondWithToken($token);
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
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function signup(Request $request)
    {
        $new_id= User::all('id')->count() + 1;
        $request['email'] = $new_id . $request->email;

        $validator = Validator::make( $request->all(),
            $rules = [
                'email' => 'required|unique:users|max:255',
                'name' => 'required',
                'password' => ['required', Password::defaults(), 'confirmed']
            ], [
                'email.required' => 'Поле email не заполнено.',
                'email.unique' => "Такой пользователь {$request['email']} уже есть.",
                'email.max' => 'Поле email слишком длинное.',
                'name.required' => 'Заполните ваше имя.',
                'password.required' => 'Заполните поле пароль.',
                'password.min' => 'Минимум 6 символов.',
                'password.confirmed' => 'Проверьте подтверждение пароля.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 401);
        }

        ;

        $user = new User([
            'name' => $new_id .'_'. $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        try {
            $user->save();

            return $this->login($request);

        } catch (Throwable $e) {

            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }


    /**
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::get();

        return response()->json(['data'=>$users]);
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
}
