<?php

namespace App\Http\Middleware;

use Closure;

class EcomApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!class_exists(\Modules\Ecommerce\Entities\EcomApiSetting::class)) {
            abort(404);
        }

        $token = $request->header('API-TOKEN');
        $is_api_settings_exists = \Modules\Ecommerce\Entities\EcomApiSetting::where('api_token', $token)
            ->exists();

        if (! $is_api_settings_exists) {
            exit('Invalid Request');
        }

        return $next($request);
    }
}
