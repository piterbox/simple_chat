<?php

namespace App\Http\Controllers;

use App\Events\SessionEvent;
use App\Http\Resources\SessionResource;
use App\Models\Session;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function store(Request $request)
    {
        $user = User::find($request->get('user_id'));
        if(!$user) return response()->json(['status' => 'error', 'message' => 'User not found']);
        $session = Session::create(['user1_id' => $user->id, 'user2_id' => auth()->user()->id, 'created_at' => Carbon::now()]);
        $modifiedSession = new SessionResource($session);
        broadcast(new SessionEvent($session, auth()->id()));
        return $modifiedSession;
    }
}
