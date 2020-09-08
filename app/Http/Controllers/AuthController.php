<?php

namespace App\Http\Controllers;

use Core\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Attempt to authenticate the user using the credentials
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ], [
            '*' => trans('auth.failed'),
        ]);

        $credentials = $request->only('email', 'password');

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $request->offsetSet('email', $request->email);

            $credentials = $request->only('email', 'password');
        }

        if (Auth::attempt($credentials)) {
            // if (Auth::user()->isDisabled() || !Auth::user()->isActivated()) {
            //     return response()->json([
            //         'message' => trans('auth.disabled'),
            //     ], 401);
            // }

            return $this->respondWithToken($request);
        }

        return response()->json([
            'message' => trans('auth.failed'),
        ], 401);
    }

    /**
     * Unauthenticate the user
     */
    public function logout(Request $request)
    {
        $user = request()->user();

        $user->tokens()->where('id', $user->currentAccessToken()->getKey())->delete();

        return response([], 204);
    }

    /**
     * Respond with a jwt bearer token.
     */
    protected function respondWithToken(Request $request)
    {
        $user = $request->user();

        $client = $user->createToken('Authentication Token');

        return response()->json([
            'access_token' => $client->plainTextToken,
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
