<?php

use App\Http\Controllers\PokemonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/pokemon', function () {
    return Inertia::render('Pokemon');
})->middleware(['auth', 'verified'])->name('pokemon');

Route::post('/pokemon', [PokemonController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('pokemon.store');

Route::patch('/pokemon/{pokemon}', [PokemonController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('pokemon.update');

Route::delete('/pokemon/{pokemon}', [PokemonController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('pokemon.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
