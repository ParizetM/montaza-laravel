<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $role_id): Response
    {
        if (! Auth::check()) {
            return redirect('login');
        }

        if (Auth::user()->hasRole($role_id)) {
            return $next($request);
        }
        return redirect('Dashboard');
    }
}
