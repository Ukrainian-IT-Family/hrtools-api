<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('Accept-Language') && in_array(
                $request->header('Accept-Language'),
                config('app.available_locale')
            )) {
            App::setLocale($request->header('Accept-Language'));
        }

        return $next($request);
    }
}
