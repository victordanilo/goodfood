<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class InjectCurrentProfileUser
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
        $user = Auth::user();
        $request->route()->parameters = array_merge(['user' => $user], $request->route()->parameters);

        return $next($request);
    }
}
