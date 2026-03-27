<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (($request->user()->user_type != 'user' || $request->user()->allow_login != 1) && request()->segment(1) != 'home') {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
