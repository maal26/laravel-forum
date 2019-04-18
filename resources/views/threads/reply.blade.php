<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div class="card mb-3" id="reply-{{ $reply->id }}">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }}</a>
                said {{ $reply->created_at->diffForHumans() }}...
            </div>
            <div>
                <favorite :reply="{{ $reply }}"></favorite>
{{--                <form action="/replies/{{ $reply->id }}/favorites" method="post">--}}
{{--                    @csrf--}}

{{--                    <button class="btn btn-sm btn-outline-primary" {{ $reply->isFavorited() ? 'disabled' : '' }}>--}}
{{--                        {{ $reply->favorites_count }} {{ \Str::plural('Favorite', $reply->favorites_count) }}--}}
{{--                    </button>--}}
{{--                </form>--}}
            </div>
        </div>
        <div v-if="editing">
            <textarea name="" class="form-control mb-2" v-model="body"></textarea>

            <div class="form-group">
                <button class="btn btn-sm btn-primary ml-2" @click="update">Update</button>
                <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
            </div>
        </div>
        <div class="card-body" v-else v-text="body">
        </div>
        @can('update', $reply)
            <div class="card-footer d-flex">
                <button class="btn btn-sm btn-info mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-sm btn-danger" @click="destroy">Delete</button>
{{--                <form action="/replies/{{ $reply->id }}" method="post">--}}
{{--                    @csrf--}}
{{--                    @method('DELETE')--}}

{{--                    <button class="btn btn-sm btn-danger">Delete</button>--}}
{{--                </form>--}}
            </div>
        @endcan
    </div>
</reply>
