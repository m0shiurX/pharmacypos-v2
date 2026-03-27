<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Timezone
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $timezone = config('app.timezone');

        if (Auth::check() && ! empty(Auth::user()->business->time_zone)) {
            $timezone = Auth::user()->business->time_zone;

            if ($request->session()->has('business')) {
                $business = $request->session()->get('business');
                $business->time_zone = $timezone;
                $request->session()->put('business', $business);
            }
        } elseif ($request->session()->has('business.time_zone')) {
            $timezone = $request->session()->get('business.time_zone');
        }

        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);

        return $next($request);
    }
}
