<?php

namespace App\Http\Resources;

use App\Models\Session;
// use App\Resources\SessionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'online' => false,
            'session' => $this->checkSession($this->id)
        ];
    }

    private function checkSession($user_id)
    {
        $session = Session::whereIn('user1_id', [auth()->id(), $user_id])
                                ->whereIn('user2_id', [auth()->id(), $user_id])
                                ->first();
        return new SessionResource($session);
    }
}
