<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketCotroller;

Route::get('/', [TicketCotroller::class, 'index'])->name('home'); // ngebuka halaman atau ngambil data dari db ke index
Route::post('simpan', [TicketCotroller::class, 'store'])->name('ticket.store'); // nerima data dari form yang diisi, disimpen nya di sini
Route::get('export-excel', [TicketCotroller::class, 'export'])->name('ticket.export');
Route::put('/update-status/{id}', [TicketCotroller::class, 'update'])->name('ticket.update');