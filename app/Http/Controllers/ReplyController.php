<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyStoreRequest;
use App\Reply;
use App\Thread;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(ReplyStoreRequest $request, $channelId, Thread $thread)
    {
        $thread->addReply([
            'body'    => $request->body,
            'user_id' => auth()->id()
        ]);

        return redirect($thread->path())->withFlash('Your reply has been left.');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(request()->all());
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
