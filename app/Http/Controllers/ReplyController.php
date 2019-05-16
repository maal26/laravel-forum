<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyStoreRequest;
use App\Inspections\Spam;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
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

    public function store(ReplyStoreRequest $request, $channelId, Thread $thread, Spam $spam)
    {
        try {
            $spam->detect($request->body);

            $reply = $thread->addReply([
                'body'    => $request->body,
                'user_id' => auth()->id()
            ]);
        } catch (\Exception $e) {
            return response()->json(
                ['message' => 'Sorry, your reply could not be saved at this time.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return response()->json($reply);
    }

    public function update(Request $request, Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

        try {
            $request->validate(['body' => 'required']);
            $spam->detect($request->body);

            $reply->update(request()->all());
        } catch (\Exception $e) {
            return response()->json(
                ['message' => 'Sorry, your reply could not be saved at this time.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
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
