<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class InjectCurrentProfileCompany
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
        $company = Auth::user();
        $request->route()->parameters = array_merge(['company' => $company], $request->route()->parameters);

        return $next($request);
    }
}
