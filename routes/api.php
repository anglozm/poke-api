<?php

use App\Http\Controllers\PokemonController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function() {
    Route::get('/user', 'index')->name('user.list');
    Route::get('/user/{user}', 'show')->name('user.show');
    Route::post('/user', 'store')->name('user.store');
    Route::patch('/user/{user}', 'update')->name('user.update');
    Route::delete('/user/{user}', 'destroy')->name('user.destroy');
});

Route::controller(PokemonController::class)->group(function() {
    Route::get('/pokemon', 'index')->name('pokemon.list');
    Route::get('/pokemon/{pokemon}', 'show')->name('pokemon.show');
    Route::post('/pokemon', 'store')->name('pokemon.store');
    Route::patch('/pokemon/{pokemon}', 'update')->name('pokemon.update');
    Route::delete('/pokemon/{pokemon}', 'destroy')->name('pokemon.destroy');
});
