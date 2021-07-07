<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GamesController;

Route::get('/', [GamesController::class, 'index'])->name('games.index');

Route::get('/games/{slug}', [GamesController::class, 'show'])->name('games.show');

//Route::get('/', 'GamesController@index')->name('games.index');

// Route::get('/', function () {
//     return view('index');
// });


// Route::get('/show', function () {
//     return view('show');
// });

