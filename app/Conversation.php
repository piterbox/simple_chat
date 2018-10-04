<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $dates = ['last_reply'];

    protected $fillable = [
        'body', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function usersExceptCurrentlyAuthenticated()
    {
        return $this->user()->where('id', '!=' , auth()->user()->id);
    }

    public function replies()
    {
        return $this->hasMany(Conversation::class, 'parent_id' )->latestFirst();
    }

    public function parent()
    {
        return $this->belongsTo(Conversation::class, 'parent_id');
    }

    public function touchLastReply()
    {
        $this->last_reply = Carbon::now();
        $this->save();
    }

    public function isReply()
    {
        return $this->parent_id !== null;
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
