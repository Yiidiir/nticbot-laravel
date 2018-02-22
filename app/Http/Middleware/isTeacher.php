<?php

namespace App\Http\Middleware;

use Closure;

class isTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->authorizeRoles('Teacher')) {
            return $next($request);
        }
        return abort(401, 'Forbidden Access');
    }
}
