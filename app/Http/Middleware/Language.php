<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = config('app.locale');
        if ($request->session()->has('user.language')) {
            $locale = $request->session()->get('user.language');
        }
        App::setLocale($locale);

        return $next($request);
    }
}
