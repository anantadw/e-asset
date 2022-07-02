<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('is_login')) {
            if (session()->get('user_role') !== 0) {
                return redirect()->route('logout');
            } else {
                return $next($request);
            }
        } else {
            return redirect()->route('logout');
        }
    }
}
