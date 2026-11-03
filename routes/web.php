<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\FilmAssetController;
use App\Http\Controllers\AssetDispatchController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {

    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // Film Assets
    Route::resource('film-assets', FilmAssetController::class);

    // Asset Dispatches
    Route::resource('asset-dispatches', AssetDispatchController::class);

    // Equipment Return
    Route::patch(
        'asset-dispatches/{assetDispatch}/return',
        [AssetDispatchController::class, 'returnEquipment']
    )->name('asset-dispatches.return');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';