<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServantController;
use App\Http\Controllers\ScheduleController;

Route::resource('categories', CategoryController::class);
Route::resource('servants', ServantController::class);
Route::resource('schedules', ScheduleController::class);

Route::get('/', function () {
    return redirect()->route('schedules.index');
});

Route::get('/schedules-by-category', [ScheduleController::class, 'byCategory'])
    ->name('schedules.by-category');
Route::get('/schedules-export-pdf', [ScheduleController::class, 'exportPdf'])
    ->name('schedules.export-pdf');
