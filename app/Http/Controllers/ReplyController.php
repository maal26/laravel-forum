<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyStoreRequest;
use App\Inspections\Spam;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

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

    public function store(ReplyStoreRequest $request, $channelId, Thread $thread, Spam $spam)
    {
        $spam->detect($request->body);

        $reply = $thread->addReply([
            'body'    => $request->body,
            'user_id' => auth()->id()
        ]);

        if (request()->wantsJson()) {
            return response()->json($reply);
        }

        return redirect($thread->path())->withFlash('Your reply has been left.');
    }

    public function update(Request $request, Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

        $request->validate(['body' => 'required']);
        $spam->detect($request->body);

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
