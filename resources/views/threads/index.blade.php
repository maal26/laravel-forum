@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($threads as $thread)
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between">
                            <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                            <a href="{{ $thread->path() }}">
                                {{ $thread->replies_count }} {{ \Str::plural('reply', $thread->replies_count) }}
                            </a>
                        </div>
                        <div class="card-body">{{ $thread->body }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
