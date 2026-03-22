<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Coach\CoachDashboardController;

// Welcome
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer pages
Route::middleware('auth.session')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/payment/{schedule_id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');

    Route::get('/activity', function () {
        return view('pages.activity');
    })->name('activity');
});

// Coach pages
Route::middleware(['auth.session', 'coach.auth'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/dashboard', [CoachDashboardController::class, 'index'])->name('dashboard');
});


// Admin pages
Route::middleware(['auth.session', 'admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/coaches', [CoachController::class, 'index'])->name('coaches');
    Route::post('/coaches', [CoachController::class, 'store'])->name('coaches.store');
    Route::delete('/coaches/{coachId}', [CoachController::class, 'destroy'])->name('coaches.destroy');
});
