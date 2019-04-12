<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Thread $thread)
    {
        $thread->addReply([
            'body'    => $request->body,
            'user_id' => auth()->id()
        ]);

        return redirect($thread->path());
    }
}
