<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Core\Models\User;
use Core\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class UsersController extends Controller
{
    /**
     * Returns a collection of User models
     */
    public function index(Request $request): JsonResource
    {
        $query = QueryBuilder::for(User::class)
            ->allowedFilters([
                'email',
                'name',
            ])
            ->allowedSorts([
                'name',
                'email',
            ]);

        return UserResource::collection($query);
    }

    /**
     * Returns a specific of User models
     */
    public function show(User $user): JsonResource
    {
        return new UserResource($user);
    }

    /**
     * Creates a new User model
     */
    public function store(Request $request): JsonResource
    {
        $this->validate($request, [
            'email' => ['requried', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
            'name' => ['required', 'max: 128'],
        ]);

        return new UserResource(
            User::create($request->all())
        );
    }

    /**
     * Updates an existing User model
     */
    public function update(Request $request, User $user): JsonResource
    {
        $this->validate($request, [
            'email' => ['requried', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
            'name' => ['required', 'max: 128'],
        ]);

        return new UserResource(
            tap($user)->update($request->all())
        );
    }

    /**
     * Deletes an existing User model
     */
    public function destroy(User $user): Response
    {
        $user->delete();

        return response([], 204);
    }
}
