<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\CategoryController; 

// ===================================================
// RUTE USER AREA (HALAMAN DEPAN)
// ===================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman daftar semua event
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// HALAMAN DETAIL EVENT (Pastikan URL ini unik dan tidak tabrakan)
Route::get('/event/detail/{id}', [EventController::class, 'show'])->name('events.show');

Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');


// ===================================================
// RUTE ADMIN AREA (DASHBOARD & OPERASI CRUD)
// ===================================================
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/events', [DashboardController::class, 'indexEvent'])->name('events.index');
    Route::get('/transactions', [DashboardController::class, 'indexTransaction'])->name('transactions.index');

    // Route CRUD Partners (Index, Create, Store, Edit, Update, Destroy)
    Route::resource('partners', PartnerController::class);

    // Route CRUD Categories via Modal
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Route CRUD Events Admin (Resource ditaruh di dalam grup agar rapi)
    Route::resource('events', EventAdminController::class);

});