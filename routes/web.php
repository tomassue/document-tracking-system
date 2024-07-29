<?php

use App\Livewire\Dashboard;
use App\Livewire\Settings\Offices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/settings/offices', Offices::class)->name('offices');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
