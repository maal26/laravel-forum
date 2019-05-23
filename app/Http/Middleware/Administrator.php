<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class Administrator
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN, 'You do not have permission to perform this action.');
    }
}
