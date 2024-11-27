<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Entite;


class GetGlobalVariable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user) {
            $notifications_count = $user->notifications()->where('read', false)->count();
            View::share('_notifications_count', $notifications_count);
            $notificationsSystem_count = $user->notifications()->where('read', false)->where('type', 'system')->count();
            View::share('_notificationsSystem_count', $notificationsSystem_count);
            $entites = Entite::all();
            View::share('_entites', $entites);
        }

        // return $next($request);
        return $next($request);
    }
}
