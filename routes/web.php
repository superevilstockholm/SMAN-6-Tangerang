<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Optional Auth
Route::middleware(['optional.auth.sanctum.cookie'])->group(function () {

});

// Protected
Route::middleware(['auth.sanctum.cookie'])->group(function () {
    // Admin
    Route::middleware(['role:admin'])->group(function () {

    });
    // Teacher
    Route::middleware(['role:teacher'])->group(function () {

    });
    // Student
    Route::middleware(['role:student'])->group(function () {

    });
});
