<?php

namespace App\Http\Controllers;

use App\Events\FriendRequestEvent;
use App\Notifications\FriendRequestNotification;
use Core\Models\FriendRequest;
use Core\Models\User;
use Core\Resources\FriendRequestResource;
use Core\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Tymon\JWTAuth\JWTAuth;

class FriendRequestController extends Controller
{
    /**
     * Returns a collection of FriendRequests related to the user
     */
    public function index(): JsonResource
    {
        return FriendRequestResource::collection(
            FriendRequest::where('user_id', Auth::user()->getKey())
                ->jsonPaginate()
        );
    }

    /**
     * Creates a new instance of FriendRequest model
     */
    public function store(Request $request): JsonResource
    {
        $this->validate($request, [
            'user_id' => 'exists:users,id'
        ]);

        $friendRequest = FriendRequest::create([
            'user_id' => $request->user_id,
            'owner_id' => Auth::user()->getKey()
        ]);

        $friendRequest->recipient
            ->notify(new FriendRequestNotification($friendRequest));

        event(new FriendRequestEvent($friendRequest));

        return new FriendRequestResource($friendRequest);
    }

    /**
     * Updates the status of the FriendRequest
     */
    public function update(Request $request, FriendRequest $friendRequest)
    {
        /**
         * Check if the user owns the FriendRequest
         */
        if ($friendRequest->owner_id === Auth::user()->getKey()) {
            return response()->json('Unauthorized', 403);
        }

        $statusRule = sprintf(
            'in:%s,%s,%s',
            FriendRequest::PENDING,
            FriendRequest::ACCEPTED,
            FriendRequest::REJECTED
        );

        $this->validate($request, [
            'status' => $statusRule
        ]);

        $friendRequest->update(['status' => $request->status]);

        return new FriendRequestResource($friendRequest);
    }
}
