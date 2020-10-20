<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Check if user an admin
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( ! Auth::user()) {
            return abort(401);
        }

        if (! Auth::user()->admin) {
            return abort(403);
        }

        return $next($request);
    }
}
