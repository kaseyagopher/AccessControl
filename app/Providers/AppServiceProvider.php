<?php

namespace App\Providers;

use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.partials.sidebar', function ($view) {
            $count = 0;
            $user = Auth::user();

            if ($user && in_array($user->role, ['superviseur', 'agent-de-security'], true)) {
                $count = Cache::remember(
                    "notifications.unread.{$user->id}",
                    60,
                    fn () => UserNotification::where('user_id', $user->id)->where('lu', false)->count()
                );
            }

            $view->with('unreadNotifications', $count);
        });
    }
}
