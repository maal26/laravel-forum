<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyStoreRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\Reply;
use App\Thread;
use Illuminate\Http\Response;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(16);
    }

    public function store(ReplyStoreRequest $request, $channelId, Thread $thread)
    {
        $reply = $thread->addReply([
            'body'    => $request->body,
            'user_id' => auth()->id()
        ]);

        return response()->json($reply, Response::HTTP_CREATED);
    }

    public function update(ReplyUpdateRequest $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update($request->validated());
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->wantsJson()) {
            return response()->json(['status' => 'Reply Deleted']);
        }

        return back();
    }
}
