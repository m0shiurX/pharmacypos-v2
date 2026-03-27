<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Ecommerce\Entities\EcomApiSetting;

class EcomApi
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! class_exists(EcomApiSetting::class)) {
            abort(404);
        }

        $token = $request->header('API-TOKEN');
        $is_api_settings_exists = EcomApiSetting::where('api_token', $token)
            ->exists();

        if (! $is_api_settings_exists) {
            exit('Invalid Request');
        }

        return $next($request);
    }
}
