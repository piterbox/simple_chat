<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'text' => $this->message['text'],
            'id' => $this->id,
            'type' => $this->type,
            'send_at' => $this->created_at->diffForHumans(),
        ];
    }
}
