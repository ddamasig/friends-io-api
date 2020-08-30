<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => ['required', 'min:5'],
            'password' => ['required', 'min:6'],
        ], [
            '*' => trans('auth.failed'),
        ]);

        $credentials = $request->only('username', 'password');

        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $request->offsetSet('email', $request->username);

            $credentials = $request->only('email', 'password');
        }

        if (Auth::attempt($credentials)) {
            if (Auth::user()->isDisabled() || !Auth::user()->isActivated()) {
                return response()->json([
                    'message' => trans('auth.disabled'),
                ], 401);
            }

            return $this->respondWithToken($request);
        }

        return response()->json([
            'message' => trans('auth.failed'),
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = request()->user();

        $user->tokens()->where('id', $user->currentAccessToken()->getKey())->delete();

        return response([], 204);
    }

    /**
     * Respond with a jwt bearer token.
     *
     * @param string $token
     *
     * @return [type] [description]
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
}
