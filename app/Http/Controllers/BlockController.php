<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;
use App\Events\SessionBlockEvent;

class BlockController extends Controller
{
    public function block(Session $session)
    {
        $session->block();
        broadcast(new SessionBlockEvent($session->id, true));
        return response('blocked', 201);
    }

    public function unblock(Session $session)
    {
        $session->unblock();
        broadcast(new SessionBlockEvent($session->id, false));
        return response('unblocked', 201);
    }

}
