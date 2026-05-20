<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminRefundController;
use App\Http\Controllers\Admin\AdminWithdrawalController;
use App\Http\Controllers\Organiser\OrganiserDashboardController;
use App\Http\Controllers\Organiser\OrganiserEventController;
use App\Http\Controllers\Client\ClientDashboardController;

// ─── PUBLIC ROUTES ─────────────────────────────────────────────
Route::get('/', function () {
    return view('home');           // ← This will be our new homepage
})->name('home');

//EVENTS
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/search', [EventController::class, 'search'])->name('events.search');

// ─── AUTH ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register',        [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',       [AuthController::class, 'register']);
    Route::get('/login',           [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',          [AuthController::class, 'login']);
    Route::get('/register/organiser', [AuthController::class, 'showOrganiserRegister'])->name('register.organiser');
    Route::post('/register/organiser',[AuthController::class, 'registerOrganiser']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── CLIENT ROUTES ──────────────────────────────────────────────
Route::middleware(['auth', 'role:client,organiser,admin'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard',               [ClientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tickets',                 [TicketController::class, 'myTickets'])->name('tickets');
    Route::get('/tickets/{ticket}',        [TicketController::class, 'show'])->name('tickets.show');

    // Reserve & Pay
    Route::get('/events/{event}/reserve',  [TicketController::class, 'reserveForm'])->name('reserve');
    Route::post('/events/{event}/reserve', [TicketController::class, 'reserve'])->name('reserve.store');
    Route::get('/tickets/{ticket}/pay',    [PaymentController::class, 'showPaymentPage'])->name('pay');
    Route::post('/tickets/{ticket}/pay',   [PaymentController::class, 'processPayment'])->name('pay.process');
    Route::get('/payment/{payment}/success',[PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{payment}/failed', [PaymentController::class, 'failed'])->name('payment.failed');

    // Refund
    Route::get('/refunds/request/{payment}',  [RefundController::class, 'create'])->name('refund.create');
    Route::post('/refunds/request/{payment}', [RefundController::class, 'store'])->name('refund.store');
    Route::get('/refunds',                    [RefundController::class, 'myRefunds'])->name('refunds');
});

// ─── ORGANISER ROUTES ───────────────────────────────────────────
Route::middleware(['auth', 'role:organiser,admin'])->prefix('organiser')->name('organiser.')->group(function () {
    Route::get('/dashboard',           [OrganiserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/events',              [OrganiserEventController::class, 'index'])->name('events');
    Route::get('/events/create',       [OrganiserEventController::class, 'create'])->name('events.create');
    Route::post('/events',             [OrganiserEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [OrganiserEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}',      [OrganiserEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}',   [OrganiserEventController::class, 'destroy'])->name('events.destroy');
    Route::get('/events/{event}/attendees', [OrganiserEventController::class, 'attendees'])->name('events.attendees');
    Route::get('/wallet',              [WithdrawalController::class, 'wallet'])->name('wallet');
    Route::get('/withdrawals/request', [WithdrawalController::class, 'create'])->name('withdrawal.create');
    Route::post('/withdrawals/request',[WithdrawalController::class, 'store'])->name('withdrawal.store');
    Route::get('/withdrawals',         [WithdrawalController::class, 'myWithdrawals'])->name('withdrawals');
});

// ─── ADMIN ROUTES ───────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',           [AdminDashboardController::class, 'index'])->name('dashboard');

    // Events
    Route::get('/events',              [AdminEventController::class, 'index'])->name('events');
    Route::get('/events/{event}',      [AdminEventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
    Route::post('/events/{event}/reject',  [AdminEventController::class, 'reject'])->name('events.reject');

    // Users
    Route::get('/users',               [AdminUserController::class, 'index'])->name('users');
    Route::get('/users/{user}',        [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/toggle',[AdminUserController::class, 'toggleActive'])->name('users.toggle');

    // Transactions
    Route::get('/transactions',        [AdminTransactionController::class, 'index'])->name('transactions');
    Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');

    // ESCROW – Release funds to organiser
    Route::get('/escrow',              [AdminTransactionController::class, 'escrow'])->name('escrow');
    Route::post('/escrow/{payment}/release', [AdminTransactionController::class, 'releaseFunds'])->name('escrow.release');

    // Refunds
    Route::get('/refunds',             [AdminRefundController::class, 'index'])->name('refunds');
    Route::get('/refunds/{refund}',    [AdminRefundController::class, 'show'])->name('refunds.show');
    Route::post('/refunds/{refund}/approve', [AdminRefundController::class, 'approve'])->name('refunds.approve');
    Route::post('/refunds/{refund}/reject',  [AdminRefundController::class, 'reject'])->name('refunds.reject');

    // Withdrawals
    Route::get('/withdrawals',         [AdminWithdrawalController::class, 'index'])->name('withdrawals');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject',  [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');
});
