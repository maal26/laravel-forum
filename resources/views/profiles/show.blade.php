@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3>
                    {{ $profileUser->name }}
                    <small class="text-muted">Since {{ $profileUser->created_at->diffForHumans() }}</small>
                    <hr>
                </h3>

                @foreach ($threads as $thread)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between">
                            <div>
                                <a href="/profiles/{{ $thread->creator->name  }}">{{ $thread->creator->name }}</a> posted:
                                <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                            </div>
                            <div>
                                <small>{{ $thread->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="card-body">{{ $thread->body }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        <span class="d-flex justify-content-center mt-3">
            {{ $threads->links() }}
        </span>
    </div>
@endsection
