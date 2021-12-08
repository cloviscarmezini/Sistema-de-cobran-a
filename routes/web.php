<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/client-login', [App\Http\Controllers\Auth\ClientLoginController::class, 'index'])->name('client.login');
Route::post('/clientLogin', [App\Http\Controllers\Auth\ClientLoginController::class, 'login'])->name('client.handle.login');
Route::get('/firstAccess', [App\Http\Controllers\Auth\ClientLoginController::class, 'firstAccess'])->name('client.firstAccessView');
Route::post('/firstAccess', [App\Http\Controllers\Auth\ClientLoginController::class, 'createFirstAccess'])->name('client.firstAccess');

Route::group(['middleware' => ['assign.guard:user']], function() {
    Route::resource('clients', ClientController::class);
    Route::resource('accounts', AccountController::class)->except('show');
    Route::get('accounts/view/{account}', [AccountController::class, 'view'])->name('accounts.view');
});

Route::group(['middleware' => ['assign.guard:client']], function() {
    Route::get('accounts/my', [AccountController::class, 'my'])->name('accounts.my');
    Route::get('accounts/trade/{account_id}', [AccountController::class, 'trade'])->name('accounts.trade');
    Route::get('accounts/installments/{account_id}', [AccountController::class, 'installments'])->name('accounts.installments');
    Route::post('accounts/trade/{account_id}', [AccountController::class, 'doTrade'])->name('accounts.doTrade');
    Route::post('accounts/payInstallment/{installment_id}', [AccountController::class, 'payInstallment'])->name('accounts.payInstallment');
});

Route::group(['middleware' => ['assign.guard:client', 'assign.guard:user']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

