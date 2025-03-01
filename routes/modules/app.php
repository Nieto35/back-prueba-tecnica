<?php

use App\Http\App\Controllers\AppController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\App\Controllers\GetArtistController;

Route::middleware('api')
    ->prefix('api')
    ->as('api.')
    ->namespace('Api')
    ->group(function (Router $router) {
        $router->get('/spotify/get-artist-by-id/{id}', '\\'. GetArtistController::class)->name('get-artist-by-id');
    });
// Entrypoint for REACT app
Route::get('/{any?}', [AppController::class, 'index'])
    ->where('any', '.*')
    ->name('index');
