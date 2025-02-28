<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//USER
use Project\Auth\Domain\Repository\UserRepository;
use Project\Auth\Infrastructure\Repository\UserRepositoryEloquent;
use Project\Auth\Domain\Mail\UserEmailSender as UserEmailSenderInterface;
use Project\Auth\Infrastructure\Mail\UserEmailSender;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // USER
        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(UserEmailSenderInterface::class, UserEmailSender::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
