<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.landing');
});

// Alkil.ar routes

Route::middleware('auth:customer')->group(function () {
    Route::get('/a/a', function () {
        return 'hola a';
    })->name('a.a');
    Route::get('/a/d', function () {
        return 'hola d';
    })->name('a.d');

    Route::get('/a/c', function () {
        Auth::guard('customer')->logout();

        return 'Hola c ';
    })->name('a.c');
});

Route::get('/a/login', function () {
    $res = Auth::guard('customer')->attempt(['email' => 'gerard33@example.net', 'password' => 'password']);

    return 'Hola login '.Auth::guard('customer')->user()->person->name;
})->name('a.login');

Route::get('/a/test', function () {
    $a = Auth::guard('customer')->check();
    $b = Auth::guard('manager')->check();
    $c = Auth::guard('employee')->check();

    return 'Hola test';
});
