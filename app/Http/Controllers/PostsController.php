<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Http\Controllers\Controller;
use App\Notifications\LikeNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Post\Models\Like;
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
            'images' => ['sometimes', 'array'],
            'friend_ids' => ['sometimes', 'array', 'exists:users,id'],
        ]);


        /**
         * Wrap inside transaction to access database rollback feature when something goes wrong
         */
        $post = DB::transaction(function () use ($request) {
            /**
             * Create the Post intance
             */
            $post = Post::create([
                'description' => $request->description,
                'uploader_id' => Auth::user()->getKey()
            ]);

            if ($request->images) {
                /**
                 * Loop through each image
                 */
                foreach ($request->images as $image) {
                    /**
                     * Validate the image
                     */
                    Validator::make([
                        'image' => $image
                    ], [
                        'image' => ['image', 'max: 10240']
                    ]);

                    /**
                     * Associate the image to the post then upload the file.
                     */
                    $post->addMedia($image)
                        ->toMediaCollection('images');
                }
            }

            return $post;
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

    /**
     * Creates a new Post model
     */
    public function like(Post $post)
    {
        /**
         * Check if the post exists
         */
        if ($post === null) {
            return response()->json([
                'message' =>  'Post does not exist.'
            ], 404);
        }

        Like::create([
            'post_id' => $post->getKey(),
            'user_id' => Auth::user()->getKey()
        ]);

        $post->uploader->notify(new LikeNotification(
            sprintf('%s liked your post.', Auth::user()->name),
            $post
        ));

        event(new LikeEvent(
            sprintf('%s liked your post.', Auth::user()->name),
            $post
        ));

        return response()->json(new PostResource($post), 200);
    }

    /**
     * Creates a new Post model
     */
    public function dislike(Post $post)
    {
        /**
         * Check if the post exists
         */
        if ($post === null) {
            return response()->json([
                'message' =>  'Post does not exist.'
            ], 404);
        }

        $post->likes()
            ->where('user_id', Auth::user()->getKey())
            ->delete();

        return response()->json(new PostResource($post), 200);
    }
}
