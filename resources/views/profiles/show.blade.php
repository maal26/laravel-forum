@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3>
                    {{ $profileUser->name }}
                    <hr>
                </h3>

                @forelse ($activities as $date => $activity)
                    <h3>{{ $date }}</h3>
                    @foreach ($activity as $record)
                        @if (view()->exists("profiles.activities.{$record->type}"))
                            @include ("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @empty
                    <p class="text-muted">There is no activity for this User yet.</p>
                @endforelse
            </div>
        </div>
        <span class="d-flex justify-content-center mt-3">
        </span>
    </div>
@endsection
