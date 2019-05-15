<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Response;

class ThreadSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channelId, Thread $thread)
    {
        $thread->subscribe();

        return response()->json(['message' => 'Subscribed'], Response::HTTP_CREATED);
    }

    public function destroy($channelId, Thread $thread)
    {
        $thread->unsubscribe();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
