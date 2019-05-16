@extends('layouts.app')

@section('content')
    <thread-view :replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div>
                                <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> posted:
                                {{ $thread->title }}
                            </div>
                            @can ('update', $thread)
                                <div>
                                    <form action="{{ $thread->path() }}" method="post">
                                        @method('DELETE')
                                        @csrf

                                        <button class="btn btn-sm btn-danger">Delete Thread</button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body">{{ $thread->body }}</div>
                    </div>

                    <replies @created="count++" @removed="count--"></replies>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            This thread was published {{ $thread->created_at->diffForHumans() }}
                            by <a href="#">{{ $thread->creator->name }}</a>, and currently
                            has <span>@{{ count }}</span> {{ Str::plural('comment', $thread->replies_count) }}.

                            @auth
                                <p>
                                    <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                                </p>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
