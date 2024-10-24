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
                    $notifications = Auth::user()->notifications()->where('read', false)->orderBy('created_at', 'desc')->take(10)->get();
                    $view->with('_notifications', $notifications);
                    $notifications_count = Auth::user()->notifications()->where('read', false)->count();
                    $view->with('_notifications_count', $notifications_count);
                    $notificationsSystem = Auth::user()->notifications()->where('read', false)->where('type', 'system')->orderBy('created_at', 'desc')->take(10)->get();
                    $view->with('_notificationsSystem', $notificationsSystem);
                    $notificationsSystem_count = Auth::user()->notifications()->where('read', false)->where('type', 'system')->count();
                    $view->with('_notificationsSystem_count', $notificationsSystem_count);
                }
            }
        );
    }
}
