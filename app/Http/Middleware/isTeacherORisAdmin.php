<?php

namespace App\Http\Middleware;

use Closure;

class isTeacherORisAdmin
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
        if ($request->user()->authorizeRoles(['Admin', 'Teacher'])) {
            return $next($request);
        }
        return abort(401, 'Forbidden Access');
    }
}
