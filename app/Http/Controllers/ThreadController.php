<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilter;
use App\Http\Requests\ThreadStoreRequest;
use App\Http\Requests\ThreadUpdateRequest;
use App\Thread;
use App\Trending;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(ThreadFilter $filters, Channel $channel, Trending $trending)
    {
        $threads  = $this->getThreads($filters, $channel);

        return view('threads.index')->with([
            'threads'  => $threads,
            'trending' => $trending->get()
        ]);
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
            'body'       => $request->body,
            'slug'       => $request->title
        ]);

        return redirect($thread->path())
            ->withFlash('Your Thread has been published');
    }

    public function show($channelId, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->visits()->record();

        return view('threads.show')->withThread($thread->append('isSubscribedTo'));
    }

    public function edit(Thread $thread)
    {
        //
    }

    public function update(ThreadUpdateRequest $request, $channelId, Thread $thread)
    {
        $thread->update($request->validated());

        return $thread;
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

        return $threads->paginate(25);
    }
}
