<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilter;
use App\Http\Requests\ThreadStoreRequest;
use App\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(ThreadFilter $filters, Channel $channel)
    {
        $threads = $this->getThreads($filters, $channel);

        return view('threads.index')->withThreads($threads);
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store(ThreadStoreRequest $request)
    {
        $thread = Thread::create([
            'user_id'    => auth()->id(),
            'channel_id' => $request->channel_id,
            'title'      => $request->title,
            'body'       => $request->body
        ]);

        return redirect($thread->path())->withFlash('Your Thread has been published');
    }

    public function show($channelId, Thread $thread)
    {
        return view('threads.show')->with([
            'thread'  => $thread,
            'replies' => $thread->replies()->paginate(20)
        ]);
    }

    public function edit(Thread $thread)
    {
        //
    }

    public function update(Request $request, Thread $thread)
    {
        //
    }

    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->replies->each->delete();
        $thread->delete();

        return redirect('/threads');
    }

    protected function getThreads(ThreadFilter $filters, Channel $channel)
    {
        $threads = Thread::filter($filters)->latest();

        if ($channel->exists) {
            $threads->whereChannelId($channel->id);
        }

        $threads = $threads->get();
        return $threads;
    }
}
