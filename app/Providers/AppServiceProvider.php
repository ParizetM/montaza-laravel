<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(
            '*',
            function ($view) {
                if (Auth::check()) {
                    $notifications = Notification::where('role_id', Auth::user()->role_id)
                        ->where('read', false)
                        ->orderBy('created_at', 'desc')
                        ->take(20)
                        ->get();
                    $view->with('_notifications', $notifications);
                    $notifications_count = Notification::where('role_id', Auth::user()->role_id)
                        ->where('read', false)
                        ->count();
                    $view->with('_notifications_count', $notifications_count);
                    $notificationsSystem = Notification::where('role_id', Auth::user()->role_id)
                        ->where('read', false)
                        ->where('type', 'system')
                        ->orderBy('created_at', 'desc')
                        ->take(20)
                        ->get();
                    $view->with('_notificationsSystem', $notificationsSystem);
                    $notificationsSystem_count = Notification::where('role_id', Auth::user()->role_id)
                        ->where('read', false)
                        ->where('type', 'system')
                        ->count();
                    $view->with('_notificationsSystem_count', $notificationsSystem_count);
                }
            }
        );
    }
}
