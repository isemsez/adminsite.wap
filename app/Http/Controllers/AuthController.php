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
        $this->middleware( 'auth:api', [ 'except' => [ 'login', 'register' ] ] );
    }


    /**
     * Create new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $user = new User();

        $rules = $user->validation_register_rules;
        $messages = $user->validation_register_messages;

        $validator = Validator::make( $request->all(), $rules, $messages );

        if ( $validator->fails() ) {
            return response()->json( [
                'message' => 'Проверьте ваши данные!',
                'errors'  => $validator->errors()
            ], 422 );
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make( $request->password );


        if ( !$user->save() ) {
            return response()->json( [ 'message' => 'Не удалось сохранить нового пользователя.', ], 500 );
        }

        return $this->login( 201 );
    }


    /**
     * Get a JWT via given credentials.
     *
     * @param int $status_code For automatic login after registering, to return status code 201.
     * @return JsonResponse
     */
    public function login(int $status_code = 200): JsonResponse
    {
        $user = new User();

        $rules = $user->validation_login_rules;
        $messages = $user->validation_login_messages;

        $validator = Validator::make( request()->all(), $rules, $messages );

        if ( $validator->fails() ) {
            return response()->json( [
                'message' => 'Проверьте ваши данные!',
                'error'   => $validator->errors()
            ], 422 );
        }

        $credentials = request( [ 'email', 'password' ] );

        if ( !$token = auth()->attempt( $credentials ) ) {
            return response()->json( [ 'message' => 'Unauthorized' ], 401 );
        }

        return $this->respondWithToken( $token, $status_code );
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

        $user = User::whereEmail( request( 'email' ) )->first();

        return response()->json( [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => $user,
        ], $status_code );
    }


    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json( auth()->user() );
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json( [ 'message' => 'Successfully logged out' ] );
    }


    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken( auth()->refresh() );
    }
}
