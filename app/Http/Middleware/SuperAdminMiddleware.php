<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->hasRole('SuperAdmin')) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
