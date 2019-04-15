<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyStoreRequest;
use App\Thread;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\ReplyStoreRequest $request
     * @param $channelId
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function store(ReplyStoreRequest $request, $channelId, Thread $thread)
    {
        $thread->addReply([
            'body'    => $request->body,
            'user_id' => auth()->id()
        ]);

        return redirect($thread->path())->withFlash('Your reply has been left.');
    }
}
