<?php

namespace App\Transformers;

use App\Conversation;
use League\Fractal\TransformerAbstract;

class ConversationTransformer extends TransformerAbstract
{
    protected $availableIncludes = [ 'replies', 'user', 'users', 'parent'];

    public function transform(Conversation $conversation)
    {
        return [
            'id' => $conversation->id,
            'body' => $conversation->body,
            'user' => $conversation->user,
            'users' => $conversation->users,
            'parent_id' => $conversation->parent ? $conversation->parent : null,
            'created_at_human' => $conversation->created_at->diffForHumans(),
            'last_reply_human' => $conversation->last_reply->diffForHumans() ? $conversation->last_reply->diffForHumans() : null,
            'participant_count' => $conversation->usersExceptCurrentlyAuthenticated()->count(),
        ];
    }

    public function includesReplies(Conversation $conversation)
    {
        return $this->collection($conversation->replies, new ConversationTransformer());
    }

    public function includesParent(Conversation $conversation)
    {
        return $this->collection($conversation->parent, new ConversationTransformer());
    }

    public function includesUser(Conversation $conversation)
    {
        return $this->item($conversation->user, new UserTransformer());
    }

    public function includesUsers(Conversation $conversation)
    {
        return $this->collection($conversation->users, new UserTransformer());
    }
}