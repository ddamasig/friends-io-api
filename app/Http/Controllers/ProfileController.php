<?php

namespace App\Http\Controllers;

use Core\Models\User;
use Core\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Tymon\JWTAuth\JWTAuth;

class ProfileController extends Controller
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
    }

    /**
     * Returns the logged in user
     */
    public function profile(): JsonResource
    {
        return new UserResource(Auth::user());
    }

    /**
     * Returns a collection of User models linked to the logged in user by Friends model
     */
    public function friends(): JsonResource
    {
        return UserResource::collection(
            QueryBuilder::for(User::class)
                ->whereIn('id', Auth::user()->friends->pluck('user_id'))
                ->jsonPaginate()
        );
    }
}
