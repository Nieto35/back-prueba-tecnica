<?php

namespace App\Providers;

use App\Http\Middleware\CheckToken;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = '\\App\\Http\\Controllers';

    public function boot(): void
    {
        parent::boot();

        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });

        Route::aliasMiddleware('check.token', CheckToken::class);
    }

    public function map(): void
    {
        $this->mapAuthRoutes();
        $this->mapAppRoutes();
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

    protected function mapAppRoutes(): void
    {

        $url = config('frontend.discovery.app');

        [$domain, $prefix] = $this->parseEnvUrl($url);
        Route::domain($domain)
            ->prefix($prefix)
            ->as('app.')
            ->namespace($this->namespace . '\\App')
            ->middleware('check.token')
            ->group(base_path('routes/modules/app.php'));
    }

    protected function parseEnvUrl(string $url): array
    {
        $segments = explode('/', $url);

        $domain = array_shift($segments);
        $prefix = join('/', $segments);

        return [$domain, $prefix];
    }
}
