<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//USER
use Project\Auth\Domain\Repository\UserRepository;
use Project\Auth\Infrastructure\Repository\UserRepositoryEloquent;
use Project\Auth\Domain\Mail\UserEmailSender as UserEmailSenderInterface;
use Project\Auth\Infrastructure\Mail\UserEmailSender;
//SPOTIFY
use Project\Shared\Domain\SpotifyHttp\HttpApiSpotify as HttpApiSpotifyInterface;
use Project\Shared\Infrastructure\SpotifyHttp\HttpApiSpotify;
//APP
use Project\App\Domain\Repository\SpotifyRepository as SpotifyRepositoryInterface;
use Project\App\Infrastructure\Repository\SpotifyRepository;

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
        // SPOTIFY
        $this->app->bind(HttpApiSpotifyInterface::class, HttpApiSpotify::class);
        // APP
        $this->app->bind(SpotifyRepositoryInterface::class, SpotifyRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
