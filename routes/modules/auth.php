<?php

use App\Http\Auth\Controllers\AppController;
use Illuminate\Support\Facades\Route;
use App\Http\Auth\Controllers\SignInController;
use Illuminate\Routing\Router;

// API routes
Route::middleware('api')
    ->prefix('api')
    ->as('api.')
    ->namespace('Api')
    ->group(function (Router $router) {
        $router->post('/auth/sign-in', '\\'. SignInController::class)->name('sign-in');
    });

// Entrypoint for REACT app
Route::get('/{any?}', [AppController::class, 'index'])
    ->where('any', '.*')
    ->name('index');
