<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['text', 'session_id', 'user_id'];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function saveforSend($session_id)
    {
        return $this->chats()->create([
            'session_id' => $session_id,
            'user_id' => auth()->user()->id,
            'type' => 0
        ]);
    }

    public function saveForReceive($session_id, $user_id)
    {
        return $this->chats()->create([
            'session_id' => $session_id,
            'user_id' => $user_id,
            'type' => 1
        ]);
    }
}
