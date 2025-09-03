<?php

use Illuminate\Support\Facades\Route;

Route::post('/api/bookings', [\App\Http\Controllers\BookingController::class,'store']);
Route::post('/api/bookings', [\App\Http\Controllers\BookingController::class,'index']);

Route::get('/', function () {
    return view('bookings');
});
