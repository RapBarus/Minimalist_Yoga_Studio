<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Coach\CoachDashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\MembershipPaymentController;

// ── Welcome ──
Route::get('/', fn() => view('welcome'))->name('welcome');

// ── Offline ──
Route::get('/offline', fn() => view('pages.offline'))->name('offline');

// ── Auth ──
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Webhook (outside auth) ──
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
Route::post('/membership/payment/webhook', [MembershipPaymentController::class, 'webhook'])->name('membership.payment.webhook');
Route::get('/membership/payment/success', [MembershipPaymentController::class, 'success'])->name('membership.payment.success');


// ── Admin ──
Route::middleware(['auth.session', 'admin.auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Coaches
    Route::get('/coaches', [CoachController::class, 'index'])->name('coaches');
    Route::post('/coaches', [CoachController::class, 'store'])->name('coaches.store');
    Route::put('/coaches/{coachId}', [CoachController::class, 'update'])->name('coaches.update');
    Route::delete('/coaches/{coachId}', [CoachController::class, 'destroy'])->name('coaches.destroy');
    Route::get('/coaches/{coachId}/detail', [CoachController::class, 'detail'])->name('coaches.detail');
    Route::post('/coaches/{coachId}/restore', [CoachController::class, 'restore'])->name('coaches.restore');
    Route::post('/coaches/{id}/add-pendapatan', [CoachController::class, 'addPendapatan'])->name('coaches.add-pendapatan');

    // Schedules
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('/schedules/{scheduleId}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    Route::get('/schedules/{scheduleId}/view', [ScheduleController::class, 'viewJadwal'])->name('schedules.view');
    Route::post('/schedules/{scheduleId}/status', [ScheduleController::class, 'updateStatus'])->name('schedules.status');
    Route::post('/schedules/{scheduleId}/peserta', [ScheduleController::class, 'addPeserta'])->name('schedules.peserta');
    Route::post('/schedules/{scheduleId}/confirm-booking/{bookingId}', [ScheduleController::class, 'confirmBooking'])->name('schedules.confirm-booking');
    Route::get('/schedules/{id}/attendance', [ScheduleController::class, 'attendance'])->name('schedules.attendance');
    Route::post('/schedules/{id}/upload-attendance', [ScheduleController::class, 'uploadAttendance'])->name('schedules.upload-attendance');

    // Classes
    Route::get('/classes', [ClassController::class, 'index'])->name('classes');
    Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
    Route::delete('/classes/{classId}', [ClassController::class, 'destroy'])->name('classes.destroy');

    // Membership
    Route::get('/membership', [MembershipController::class, 'index'])->name('membership');
    Route::post('/membership', [MembershipController::class, 'store'])->name('membership.store');
    Route::post('/membership/{id}/toggle', [MembershipController::class, 'toggleActive'])->name('membership.toggle');
    Route::delete('/membership/{id}', [MembershipController::class, 'destroy'])->name('membership.destroy');
    Route::get('/membership/{id}/view', [MembershipController::class, 'view'])->name('membership.view');
    Route::get('/membership/{id}/view', [MembershipController::class, 'view'])->name('membership.view');
    Route::put('/membership/{id}/update', [MembershipController::class, 'update'])->name('membership.update');

    // Promos
    Route::get('/promos', [PromoController::class, 'index'])->name('promos');
    Route::post('/promos', [PromoController::class, 'store'])->name('promos.store');
    Route::post('/promos/{id}/toggle', [PromoController::class, 'toggleActive'])->name('promos.toggle');
    Route::delete('/promos/{id}', [PromoController::class, 'destroy'])->name('promos.destroy');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customers/{userId}/detail', [CustomerController::class, 'detail'])->name('customers.detail');
    Route::delete('/customers/stop-membership', [CustomerController::class, 'stopMembership'])->name('customers.stop-membership');
    Route::delete('/customers/cancel-booking', [CustomerController::class, 'cancelBooking'])->name('customers.cancel-booking');

    // Keuangan
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan');
});

// ── Coach ──
Route::middleware(['auth.session', 'coach.auth'])->prefix('coach')->name('coach.')->group(function () {

    Route::get('/dashboard', [CoachDashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedule/{scheduleId}', [CoachDashboardController::class, 'scheduleDetail'])->name('schedule.detail');
    Route::post('/schedule/{scheduleId}/update', [CoachDashboardController::class, 'updateSchedule'])->name('schedule.update');
    Route::get('/profile', fn() => 'Coming soon')->name('profile');
});

// ── Customer ──
Route::middleware('auth.session')->group(function () {

    // Main pages
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity');
    Route::get('/member', [MemberController::class, 'index'])->name('member');
    Route::get('/coach/{coachId}', [HomeController::class, 'coachProfile'])->name('coach.show');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Payment
    Route::get('/payment/success/{bookingId}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed/{bookingId}', [PaymentController::class, 'failed'])->name('payment.failed');
    Route::get('/payment/receipt/{bookingId}', [PaymentController::class, 'receipt'])->name('payment.receipt');
    Route::get('/payment/instructions/{transactionId}', [PaymentController::class, 'instructions'])->name('payment.instructions');
    Route::get('/payment/check/{transactionId}', [PaymentController::class, 'check'])->name('payment.check');
    Route::get('/payment/cancel/{bookingId}', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::get('/payment/method/{schedule_id}', [PaymentController::class, 'showMethod'])->name('payment.method');
    Route::post('/payment/method/{schedule_id}', [PaymentController::class, 'processMethod'])->name('payment.method.process');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{schedule_id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/use-quota', [PaymentController::class, 'useQuota'])->name('payment.use.quota');

    // Membership payment
    Route::get('/membership/payment/{package_id}', [MembershipPaymentController::class, 'show'])->name('membership.payment.show');
    Route::get('/membership/payment/{package_id}/method', [MembershipPaymentController::class, 'showMethod'])->name('membership.payment.method');
    Route::post('/membership/payment/{package_id}/method', [MembershipPaymentController::class, 'processMethod'])->name('membership.payment.method.process');
    Route::get('/membership/payment/{package_id}/failed', [MembershipPaymentController::class, 'failed'])->name('membership.payment.failed');
});
