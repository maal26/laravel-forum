@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <a href="#">{{ $thread->creator->name }}</a> posted:
                        {{ $thread->title }}
                    </div>
                    <div class="card-body">{{ $thread->body }}</div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            @foreach ($thread->replies as $reply)
                @include ('threads.reply')
            @endforeach
        </div>

        @auth
            <div class="row justify-content-center">
                <form action="{{ $thread->path('replies') }}" method="post" class="w-50">
                    @csrf

                    <textarea name="body" rows="5" class="form-control" placeholder="Have something to say"></textarea>
                    <button class="btn btn-primary mt-3">Post</button>
                </form>
            </div>
        @endauth

        @guest
            <p class="text-center">Please <a href="{{ route('login') }}">sign in </a> to participate in this discussion</p>
        @endguest
    </div>
@endsection
