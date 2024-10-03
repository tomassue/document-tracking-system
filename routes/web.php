<?php

use App\Http\Controllers\HomeController;
use App\Livewire\CPSO\Calendar;
use App\Livewire\CPSO\Dashboard;
use App\Livewire\CPSO\Incoming\Documents;
use App\Livewire\CPSO\Incoming\Request;
use App\Livewire\CPSO\Outgoing;
use App\Livewire\Settings\Category;
use App\Livewire\Settings\ChangePassword;
use App\Livewire\Settings\Offices;
use App\Livewire\Settings\UserManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/404', [HomeController::class, 'notFoundPage'])->name('404');

Auth::routes();

/* -------------------------------------------------------------------------- */
/*                                    CPSO                                    */
/* -------------------------------------------------------------------------- */

Route::middleware(['is_active', 'auth', 'updated_password', 'cpso_access_only'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/incoming/requests', Request::class)->name('requests');
    Route::get('/incoming/documents', Documents::class)->name('documents');
    Route::get('/outgoing', Outgoing::class)->name('outgoing');
    Route::get('/calendar', Calendar::class)->name('calendar');
});


/* -------------------------------------------------------------------------- */
/*                                 SUPERADMIN                                 */
/* -------------------------------------------------------------------------- */

Route::middleware(['is_active', 'auth', 'super_admin_access_only', 'updated_password'])->group(function () {
    Route::get('/settings/category', Category::class)->name('category');
    Route::get('/settings/offices', Offices::class)->name('offices');
    Route::get('/settings/user-management', UserManagement::class)->name('user-management');
});

/* -------------------------------------------------------------------------- */
/*                                 OPEN ROUTES                                */
/* -------------------------------------------------------------------------- */

Route::group(['is_active', 'middleware' => 'auth'], function () {
    Route::get('/settings/change-password', ChangePassword::class)->name('change-password');
});



// Livewire::setScriptRoute(function ($handle) {
//     return Route::get('/cdo-dts/livewire/livewire.js', $handle);
// });
// Livewire::setUpdateRoute(function ($handle) {
//     return Route::post('/cdo-dts/livewire/update', $handle);
// });
