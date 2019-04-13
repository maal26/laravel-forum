<?php

namespace App\Filters;

use App\User;
use Illuminate\Database\Eloquent\Builder;

final class ThreadFilter extends Filters
{
    protected $filters = ['by', 'popular'];

    /**
     * @param string $username
     * @return Builder
     */
    protected function by(string $username): Builder
    {
        $user = User::whereName($username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * @param $number
     * @return Builder
     */
    protected function popular($number): Builder
    {
        return is_int($number) && $number === 1
            ? $this->builder->orderByDesc('replies_count')
            : $this->builder;
    }
}
