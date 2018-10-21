<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['session_id', 'message_id', 'user_id', 'type', 'read_at'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
