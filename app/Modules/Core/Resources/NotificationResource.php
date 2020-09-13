<?php

namespace Core\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'read_at'    => $this->read_at,
            'data'    => $this->data,
            'created_at' => $this->created_at->toISO8601String()
        ];
    }
}
