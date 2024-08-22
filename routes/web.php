<?php

use App\Http\Controllers\PdfController;
use App\Livewire\Dashboard;
use App\Livewire\Incoming\Documents;
use App\Livewire\Incoming\Request;
use App\Livewire\Outgoing;
use App\Livewire\Settings\Category;
use App\Livewire\Settings\Offices;
use App\Livewire\Settings\UserManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/incoming/requests', Request::class)->name('requests');
    Route::get('/incoming/documents', Documents::class)->name('documents');
    Route::get('/outgoing', Outgoing::class)->name('outgoing');

    /* -------------------------------------------------------------------------- */
    /*                                 SUPERADMIN                                 */
    /* -------------------------------------------------------------------------- */
    Route::get('/settings/category', Category::class)->name('category');
    Route::get('/settings/offices', Offices::class)->name('offices');
    Route::get('/settings/user-management', UserManagement::class)->name('user-management');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
