<?php

namespace App\Http\Controllers;

use App\Events\PrivateChatEvent;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\Session;
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
        return response($message, 200);
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
       }
     
    }

}
