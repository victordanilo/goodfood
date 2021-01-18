<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class InjectCurrentProfileCustomer
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
        $customer = Auth::user();
        $request->route()->parameters = array_merge(['customer' => $customer], $request->route()->parameters);

        return $next($request);
    }
}
