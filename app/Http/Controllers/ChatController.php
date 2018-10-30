<?php

namespace App\Http\Controllers;

use App\Events\PrivateChatEvent;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\Session;
use App\Events\MessageReadEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    public function send(Session $session, Request $request)
    {
        $message = $session->messages()->create(['text' => $request->content, 'user_id' => auth()->user()->id]);
        $chat = $message->saveForSend($session->id);
        $message->saveForReceive($session->id, $request->to_user);
        broadcast(new PrivateChatEvent($message->text, $chat));
        return response($chat->id, 200);
    }

    public function chats(Session $session)
    {
        return ChatResource::collection($session->chats->where('user_id', auth()->id()));
    }

    public function read(Session $session)
    {
       $chats = $session->chats->where('read_at', null)->where('type', 0)->where('user_id', '!=', auth()->id());
      
       foreach($chats as $chat){
           $chat->update(['read_at' => Carbon::now()]);
           broadcast(new MessageReadEvent(new ChatResource($chat), $chat->session_id));
       }
     
    }

    public function clear(Session $session)
    {
        $session->deleteChats();
        $session->chats()->count() == 0 ? $session->deleteMessages() : null;
        return response('cleared', 200);
    }

    
}
