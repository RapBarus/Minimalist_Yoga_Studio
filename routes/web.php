<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\PromotionController;
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
    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');
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
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Coaches
    Route::get('/coaches', [CoachController::class, 'index'])->name('coaches');
    Route::post('/coaches', [CoachController::class, 'store'])->name('coaches.store');
    Route::post('/coaches/{coachId}/restore', [CoachController::class, 'restore'])->name('coaches.restore');
    Route::delete('/coaches/{coachId}', [CoachController::class, 'destroy'])->name('coaches.destroy');

    // Schedules
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::post('/schedules/{scheduleId}/status', [ScheduleController::class, 'updateStatus'])->name('schedules.status');
    Route::delete('/schedules/{scheduleId}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // Classes
    Route::get('/classes', [ClassController::class, 'index'])->name('classes');
    Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
    Route::delete('/classes/{classId}', [ClassController::class, 'destroy'])->name('classes.destroy');

    // Promotions
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions');
    Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
    Route::post('/promotions/{id}/toggle', [PromotionController::class, 'toggleActive'])->name('promotions.toggle');
    Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('promotions.destroy');
});
