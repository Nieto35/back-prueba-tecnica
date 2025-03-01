<?php

use App\Http\App\Controllers\AppController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use App\Http\App\Controllers\GetArtistController;
use App\Http\App\Controllers\GetArtistAlbumsController;
use App\Http\App\Controllers\GetAudioBookController;
use App\Http\App\Controllers\GetAlbumController;

Route::middleware('api')
    ->prefix('api')
    ->as('api.')
    ->namespace('Api')
    ->group(function (Router $router) {
        Route::prefix('spotify')
            ->group(function (Router $router) {
                $router->get('/{id}/get-artist', '\\'. GetArtistController::class)->name('get-artist-by-id');
                $router->get('/artists/{id}/albums', '\\'. GetArtistAlbumsController::class)->name('get-artist-albums');
                $router->get('/{id}/audiobooks', '\\'. GetAudioBookController::class)->name('get-audiobook-by-id');
                $router->get('/{id}/album/', '\\'. GetAlbumController::class)->name('get-album-by-id');
            });
    });
// Entrypoint for REACT app
Route::get('/{any?}', [AppController::class, 'index'])
    ->where('any', '.*')
    ->name('index');
