<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::view('/', 'welcome')->name('welcome');

Route::middleware('jwt.auth')->group(function () {
    //Route::view('profile', 'profile')->name('profile');
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware(
        'jwt.rp:Vendedor|Gestionar Clientes,Reportes Clientes'
    )->group(function () {
        Route::view('clients', 'clients')->name('clients');
        Route::get('/clients/pdf', [
            ClientController::class,
            'GenerarPDF',
        ])->name('clients.pdf');
    });

    // Route::get("/invoices", function () {
    //     return view("invoices.index");
    // })->name("invoices.index");

    Route::middleware('jwt.rp:Vendedor|Reportes Facturas')->group(function () {
        Route::view('invoices', 'livewire.invoices.index')->name('invoices');
        //     Route::get('/invoices/pdf', [
        //     InvoiceController::class,
        //     'GenerarPDF',
        // ])->name('invoice.pdf');
    });
});

require __DIR__ . '/auth.php';
