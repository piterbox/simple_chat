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
            'read_at' => $this->readAtTiming($this),
            'send_at' => $this->created_at->diffForHumans(),
        ];
    }

    private function readAtTiming($_this)
    {
        $read_at = $_this->type == 0 ? $_this->read_at : null;
        return $read_at ? $read_at->diffForHumans() : null;
    }
}
