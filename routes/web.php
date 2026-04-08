<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketCotroller;
use App\Http\Controllers\AuthController;


// Public
Route::get('/', [TicketCotroller::class, 'create'])->name('ticket.create');
Route::post('simpan', [TicketCotroller::class, 'store'])->name('ticket.store'); // nerima data dari form yang diisi, disimpen nya di sini

// Auth
Route::get('/login-it', [AuthController::class, 'showLogin'])->name('login');
Route::post('/cek-login', [AuthController::class, 'cekPin'])->name('cekLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin
Route::middleware(['cek.admin'])->group(function () {
    
    // Nampilin Dashboard lengkap dengan tabel dan filter (Pindahan dari route '/' sebelumnya)
    Route::get('/dashboard', [TicketCotroller::class, 'index'])->name('dashboard');
    Route::get('/dashboard/ajax-tickets', [TicketCotroller::class, 'getAjaxTickets'])->name('ajax.tickets');
    
    // Fitur-fitur operasional tiket lu
    Route::put('/update-status/{id}', [TicketCotroller::class, 'update'])->name('ticket.update');
    Route::delete('/ticket/{id}', [TicketCotroller::class, 'destroy'])->name('ticket.destroy');
    Route::get('/export-excel', [TicketCotroller::class, 'export'])->name('ticket.export');

});