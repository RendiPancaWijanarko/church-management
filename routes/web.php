<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServantController;

Route::get('/', function () {
    return redirect()->route('servants.index');
});

Route::resource('categories', CategoryController::class);
Route::resource('servants', ServantController::class);
