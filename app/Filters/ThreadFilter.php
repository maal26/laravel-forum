<?php

namespace App\Filters;

use App\User;
use Illuminate\Database\Eloquent\Builder;

final class ThreadFilter extends Filters
{
    protected $filters = ['by', 'popular', 'unanswered'];

    protected function by(string $username): Builder
    {
        $user = User::whereName($username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    protected function popular($number): Builder
    {
        return is_int($number) && $number === 1
            ? $this->builder->orderByDesc('replies_count')
            : $this->builder;
    }

    protected function unanswered(): Builder
    {
        return $this->builder->doesntHave('replies');
    }
}
