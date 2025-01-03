<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'     => 'dashboard',
        'as'         => 'dashboard.',
    ],
    function (): void {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('index');
    }
);
