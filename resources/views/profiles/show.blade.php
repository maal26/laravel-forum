@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <avatar-form :profile="{{ $profileUser }}"></avatar-form>

                @forelse ($activities as $date => $activity)
                    <h3 class="mt-3">{{ $date }}</h3>
                    @foreach ($activity as $record)
                        @if (view()->exists("profiles.activities.{$record->type}"))
                            @include ("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @empty
                    <p class="text-muted mt-3">There is no activity for this User yet.</p>
                @endforelse
            </div>
        </div>
        <span class="d-flex justify-content-center mt-3">
        </span>
    </div>
@endsection
