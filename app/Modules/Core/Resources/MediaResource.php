<?php

namespace Core\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'url'    => $this->getUrl(),
            'file_name'    => $this->file_name
        ];
    }
}
