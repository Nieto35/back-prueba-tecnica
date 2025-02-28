<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = '\\App\\Http\\Controllers';

    public function map(): void
    {
        $this->mapAuthRoutes();
    }

    protected function mapAuthRoutes(): void
    {

        $url = config('frontend.discovery.auth');

        [$domain, $prefix] = $this->parseEnvUrl($url);
        Route::domain($domain)
            ->prefix($prefix)
            ->as('auth.')
            ->namespace($this->namespace . '\\Auth')
            ->group(base_path('routes/modules/auth.php'));
    }

    protected function parseEnvUrl(string $url): array
    {
        $segments = explode('/', $url);

        $domain = array_shift($segments);
        $prefix = join('/', $segments);

        return [$domain, $prefix];
    }
}
