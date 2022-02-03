<?php

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
    return view('welcome');
});

//Auth::routes();

Route::name('logout')->post('/logout', [
    App\Http\Controllers\Auth\LoginController::class,
    'logout'
]);

Route::name('login')->get('/login', [
    App\Http\Controllers\Auth\LoginController::class,
    'showLoginForm'
]);

Route::name('auth.webex')->get('/auth/webex', [
    App\Http\Controllers\Auth\LoginController::class,
    'webexOauthRedirect'
]);

Route::get('/auth/webex/callback', [
    App\Http\Controllers\Auth\LoginController::class,
    'webexOauthCallback'
]);

Route::post('/auth/webex', [
    App\Http\Controllers\Auth\LoginController::class,
    'login'
]);

Route::name('home')->get('/home', [
    App\Http\Controllers\HomeController::class,
    'index'
]);
