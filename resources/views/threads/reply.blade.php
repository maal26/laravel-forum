<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <a href="#">{{ $reply->owner->name }}</a>
            said {{ $reply->created_at->diffForHumans() }}...
        </div>
        <div>
            <form action="/replies/{{ $reply->id }}/favorites" method="post">
                @csrf

                <button class="btn btn-sm btn-outline-primary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ $reply->favorites()->count() }} {{ \Str::plural('Favorite', $reply->favorites()->count()) }}
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">{{ $reply->body }}</div>
</div>
