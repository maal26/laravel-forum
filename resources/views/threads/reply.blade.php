<div class="card mb-3" id="reply-{{ $reply->id }}">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }}</a>
            said {{ $reply->created_at->diffForHumans() }}...
        </div>
        <div>
            <form action="/replies/{{ $reply->id }}/favorites" method="post">
                @csrf

                <button class="btn btn-sm btn-outline-primary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ $reply->favorites_count }} {{ \Str::plural('Favorite', $reply->favorites_count) }}
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">{{ $reply->body }}</div>
    @can('update', $reply)
        <div class="card-footer">
            <form action="/replies/{{ $reply->id }}" method="post">
                @csrf
                @method('DELETE')

                <button class="btn btn-sm btn-danger">Delete</button>
            </form>
        </div>
    @endcan
</div>
