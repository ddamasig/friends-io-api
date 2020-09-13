<?php

namespace Core\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FriendRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->getKey(),
            'user_id'    => $this->user_id,
            'owner_id'    => $this->owner_id,
            'status'    => $this->status,
            'sender'    => new UserResource($this->sender),
            'created_at' => $this->created_at->toISO8601String()
        ];
    }
}
