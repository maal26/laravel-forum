<?php

namespace App\Filters;

use App\User;
use Illuminate\Database\Eloquent\Builder;

final class ThreadFilter extends Filters
{
    protected $filters = ['by'];

    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function by(string $username): Builder
    {
        $user = User::whereName($username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }
}
