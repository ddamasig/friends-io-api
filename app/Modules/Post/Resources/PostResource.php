<?php

namespace Post\Resources;

use Core\Resources\MediaResource;
use Core\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'uploader_id'    => $this->uploader_id,
            'title'    => $this->title,
            'description'    => $this->description,
            'uploader'  => $this->whenLoaded('uploader', new UserResource($this->uploader)), 
            'images'  => MediaResource::collection($this->getMedia('images')),
            'created_at' => $this->created_at
        ];
    }
}
