<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\CategoryController; 
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TransactionController;

// ===================================================
// RUTE USER AREA (HALAMAN DEPAN)
// ===================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/event/detail/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// ===================================================
// RUTE ADMIN AREA (DASHBOARD & OPERASI CRUD)
// ===================================================
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    // 1. Rute Login (Bebas Akses)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // 2. Rute Terlindungi (Gunakan middleware 'admin')
    Route::middleware(['admin'])->group(function () {
        
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
        
        // CRUD Partners
        Route::resource('partners', PartnerController::class);
        
        // CRUD Events (Admin)
        Route::resource('events', EventAdminController::class);
        
        // Transactions
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        
        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});