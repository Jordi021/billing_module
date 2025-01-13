<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::view('/', 'welcome')->name('welcome');

Route::middleware(['jwt.auth', 'jwt.rp:Vendedor'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    //Route::view('profile', 'profile')->name('profile');

    Route::view('clients', 'clients')->name('clients');

    Route::get('/invoices', function () {
        return view('invoices.index');
    })->name('invoices.index');

    Route::get('/clients/pdf', [ClientController::class, 'GenerarPDF'])->name(
        'clients.pdf'
    );
});

require __DIR__ . '/auth.php';
