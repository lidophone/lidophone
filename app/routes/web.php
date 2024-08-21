<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\MetroStationsController;
use App\Http\Controllers\ObjectsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'index']);

    Route::prefix('/api/v1')->group(function () {
        Route::get('/metro/stations/under-construction', [MetroStationsController::class, 'underConstruction']);
        Route::get('/objects/search', [ObjectsController::class, 'search']);
        Route::get('/objects/get/{id}', [ObjectsController::class, 'get']);
        Route::get('/event-trigger', [MainController::class, 'eventTrigger']);
    });

    Route::post('/enable-expert-mode', [MainController::class, 'enableExpertMode'])->name('enableExpertMode');
    Route::get('/daily-rating', [MainController::class, 'dailyCompetition']);
    Route::get('/phpinfo', [MainController::class, 'phpinfo']);
    Route::get('/uis-call/{id}', [MainController::class, 'uisCall']);
});

require __DIR__.'/auth.php';
