<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ( !$request->user()->isStudent() ) {
            if ( $request->expectsJson() ) {
                return redirect()->route('api.error.401');
            }

            return redirect('/staff');
        }

        return $next($request);
    }
}
