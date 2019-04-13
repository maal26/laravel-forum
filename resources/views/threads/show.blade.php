@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <a href="#">{{ $thread->creator->name }}</a> posted:
                        {{ $thread->title }}
                    </div>
                    <div class="card-body">{{ $thread->body }}</div>
                </div>

                @foreach ($replies as $reply)
                    @include ('threads.reply')
                @endforeach

                <span class="d-flex justify-content-center">{{ $replies->links() }}</span>

                @auth
                    <form action="{{ $thread->path('replies') }}" method="post" class="w-100">
                        @csrf

                        <textarea
                            name="body"
                            rows="5"
                            class="form-control"
                            placeholder="Have something to say?">
                        </textarea>
                        <button class="btn btn-primary mt-3">Post</button>
                    </form>
                @endauth

                @guest
                    <p class="text-center">
                        Please <a href="{{ route('login') }}">sign in </a> to participate in this discussion
                    </p>
                @endguest

            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        This thread was published {{ $thread->created_at->diffForHumans() }}
                        by <a href="#">{{ $thread->creator->name }}</a>, and currently
                        has {{ $thread->replies_count }} {{ Str::plural('comment', $thread->replies_count) }}.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
