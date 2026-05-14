<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Mail\NewUserRegisteredAdminNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

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
        // Super-admin bypass — can do everything
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Catch the registration event and dispatch an email to the admin
        Event::listen(Verified::class, function (Verified $event) {
            
            $adminEmail = config('mail.admin_address', 'admin@yourdomain.com');
            
            Mail::to($adminEmail)->send(
                new NewUserRegisteredAdminNotification($event->user)
            );
        });
    }
}
