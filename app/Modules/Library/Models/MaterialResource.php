<?php

namespace Library\Resources;

use Core\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
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
            'parent_id'    => $this->parent_id,
            'uploader_id'    => $this->uploader_id,
            'title'    => $this->title,
            'description'    => $this->descrption,
            'rating'    => $this->rating,
            'date_published'    => $this->date_published->toISO8601String(),
            'uploader'  => $this->whenLoaded('uploader', new UserResource($this->uploader)), 
            'parent'  => $this->whenLoaded('parent', new MaterialResource($this->parent)), 
        ];
    }
}
