@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @forelse($threads as $thread)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between">
                            <a href="{{ $thread->path() }}">
                                @if (auth()->check() && $thread->hasUpdateFor(auth()->user()))
                                    <strong>{{ $thread->title }}</strong>
                                @else
                                    {{ $thread->title }}
                                @endif
                            </a>
                            <a href="{{ $thread->path() }}">
                                {{ $thread->replies_count }} {{ \Str::plural('reply', $thread->replies_count) }}
                            </a>
                        </div>
                        <div class="card-body">{{ $thread->body }}</div>
                    </div>
                @empty
                    <p>There are no relevant result as this time.</p>
                @endforelse
                    <span class="d-flex justify-content-center">
                        {{ $threads->links() }}
                    </span>
            </div>

            <div class="col-md-4">
                @if (count($trending))
                    <div class="card">
                        <div class="card-header">
                            Trending Threads
                        </div>
                        <div class="card-body">
                            @foreach ($trending as $thread)
                                <li class="list-group-item">
                                    <a href="{{ url($thread->path) }}">
                                        {{ $thread->title }}
                                    </a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
