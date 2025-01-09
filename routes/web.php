<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::view("/", "welcome");

Route::view("dashboard", "dashboard")
    ->middleware(["auth", "verified"])
    ->name("dashboard");

Route::view("profile", "profile")
    ->middleware(["auth"])
    ->name("profile");

Route::view("clients", "clients")
    ->middleware(["auth"])
    ->name("clients");

Route::get("/invoices", function () {
    return view("invoices.index");
})->name("invoices.index");

Route::get("/clients/pdf", [ClientController::class, "GenerarPDF"])->name(
    "clients.pdf"
);

require __DIR__ . "/auth.php";
