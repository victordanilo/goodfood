<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class ScopeValidate
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
        $guardName = Auth::getDefaultDriver();
        if (! $request->user()->tokenCan($guardName)) {
            return response()->json([
                'message' => __('auth.not_authenticated'),
            ]);
        }

        return $next($request);
    }
}
