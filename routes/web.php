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

Route::group(['middleware' => ['assign.guard:user']], function() {
    Route::resource('clients', ClientController::class);
    Route::resource('accounts', AccountController::class)->except('show');
});

Route::group(['middleware' => ['assign.guard:client']], function() {
    Route::get('accounts/my', [AccountController::class, 'my'])->name('accounts.my');
});

Route::group(['middleware' => ['assign.guard:client', 'assign.guard:user']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

