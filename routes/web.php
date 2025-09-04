<?php

use Illuminate\Support\Facades\Route;

Route::post('/api/bookings', [\App\Http\Controllers\BookingController::class,'store']);
Route::get('/api/bookings', [\App\Http\Controllers\BookingController::class, 'weekly']);

Route::get('/', function () {
    return view('bookings');
});
