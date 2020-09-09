<?php

namespace App\Http\Controllers;

use Core\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    protected $auth;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $auth)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->auth = $auth;
    }

    /**
     * Attempt to authenticate the user using the credentials
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => ['sometimes', 'required', 'email'],
            'password' => ['sometimes', 'required', 'min:8'],
            'token' => ['sometimes', 'required'],
        ]);

        $credentials = $request->only('email', 'password');

        if ($request->input('token')) {
            $this->auth->setToken($request->input('token'));

            $user = $this->auth->authenticate();

            if ($user) {
                return $this->respondWithToken($request);
            }
        }

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'error' => [
                    'message' => 'Email and password did not match. Please try again.'
                ]
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Respond with a jwt bearer token.
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Returns the logged in user
     */
    public function profile(): JsonResource
    {
        return new UserResource(Auth::user());
    }
}
