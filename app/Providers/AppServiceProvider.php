<?php

namespace App\Providers;

use App\Models\Entite;
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
                $user = Auth::user();
                if ($user) {
                    $notifications = $user->notifications()->where('read', false)->orderBy('created_at', 'desc')->take(10)->get();
                    $view->with('_notifications', $notifications);
                    $notifications_count = $user->notifications()->where('read', false)->count();
                    $view->with('_notifications_count', $notifications_count);
                    $notificationsSystem = $user->notifications()->where('read', false)->where('type', 'system')->orderBy('created_at', 'desc')->take(10)->get();
                    $view->with('_notificationsSystem', $notificationsSystem);
                    $notificationsSystem_count = $user->notifications()->where('read', false)->where('type', 'system')->count();
                    $view->with('_notificationsSystem_count', $notificationsSystem_count);
                    $entites = Entite::all();
                    $view->with('_entites', $entites);
                }
            }
        );
    }
}
