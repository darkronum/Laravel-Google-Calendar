<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Auth\CalendarController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('auth.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/google/login', [SocialController::class, 'redirectOnGoogle'])->name('google.login');
    Route::get('/google/redirect', [SocialController::class, 'openGoogleAccountDetails'])->name('google.callback');
    //Route::get('calendar', [CalendarController::class, 'openCalendar'])->name('calendar');
    Route::get('test', [CalendarController::class, 'openCalendar'])->name('auth.calendar.index');
    Route::resource('calendar', CalendarController::class);

});



require __DIR__.'/auth.php';


