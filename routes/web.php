<?php

use Illuminate\Support\Facades\Route;
use Dedoc\Scramble\Http\Controllers\DocsController;

Route::middleware('web')
    ->prefix('docs')
    ->group(function () {
        Route::get('/api', [DocsController::class, 'show']);
        Route::get('/api.json', [DocsController::class, 'json']);
    });
