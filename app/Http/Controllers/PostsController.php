<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Core\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Post\Models\Post;
use Post\Resources\PostResource;
use Spatie\QueryBuilder\QueryBuilder;

class PostsController extends Controller
{
    /**
     * Returns a collection of Post models
     */
    public function index(Request $request): JsonResource
    {
        $query = QueryBuilder::for(Post::class)
            ->allowedFilters([
                'title',
                'description',
                'uploader_id',
            ])
            ->allowedIncludes([
                'uploader'
            ])
            ->orderBy('created_at', 'desc')
            ->jsonPaginate();

        return PostResource::collection($query);
    }

    /**
     * Returns a specific of Post models
     */
    public function show(Post $post): JsonResource
    {
        return new PostResource($post);
    }

    /**
     * Creates a new Post model
     */
    public function store(Request $request): JsonResource
    {
        $this->validate($request, [
            'description' => ['required', 'max: 2048'],
            'images' => ['sometimes', 'image'],
            'friend_ids' => ['sometimes', 'array', 'exists:users,id'],
        ]);


        $post = DB::transaction(function () use ($request) {
            return Post::create([
                'description' => $request->description,
                'uploader_id' => Auth::user()->getKey()
            ]);
        });

        return new PostResource($post);
    }

    /**
     * Updates an existing Post model
     */
    public function update(Request $request, Post $post): JsonResource
    {
        $this->validate($request, [
            'parent_id' => ['integer'],
            'title' => ['required', 'max:256'],
            'description' => ['required', 'max: 2048'],
            'date_published' => ['required', 'date'],
        ]);

        return new PostResource(
            tap($post)->update($request->all())
        );
    }

    /**
     * Deletes an existing Post model
     */
    public function destroy(Post $post): Response
    {
        $post->delete();

        return response([], 204);
    }
}
