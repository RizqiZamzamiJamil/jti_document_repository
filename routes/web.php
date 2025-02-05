<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Setting\AccountController;
use App\Http\Controllers\Master\JurusanController;
use App\Http\Controllers\Master\ProdiController;
use App\Http\Controllers\Setting\GroupController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Setting\UserController;


Route::pattern('INV', '[A-Z0-9]{12}'); // Filter Parameter INVOICE
Route::pattern('theme', '[a-z]+'); // Filter Parameter INVOICE
Route::pattern('id', '[0-9]+'); // Filter Parameter GET
Route::pattern('detail_id', '[0-9]+'); // Filter Parameter GET
Route::pattern('sub_detail_id', '[0-9]+'); // Filter Parameter GET
Route::pattern('uuid', '[A-Fa-f0-9]{32}$'); // Filter Parameter GET
Route::pattern('date', '[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])'); // Filter Parameter GET

Auth::routes(['register' => false, 'confirm' => false, 'email' => false, 'reset' => false]);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
Route::get('/login-sso', [App\Http\Controllers\Auth\LoginController::class, 'showLoginSSO']);
Route::get('/attempt-sso', [App\Http\Controllers\Auth\LoginController::class, 'attemptLoginSSO']);

Route::get('/', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/home', [DashboardController::class, 'index'])->middleware('auth');

// theme
Route::get('/theme/{theme}', [\App\Http\Controllers\Setting\ThemeController::class, 'index']);

Route::group(['prefix' => 'setting', 'middleware' => ['auth']], function () {
    // account
    Route::get('account', [AccountController::class, 'index']);
    Route::put('account', [AccountController::class, 'update']);
    Route::put('account/avatar', [AccountController::class, 'update_avatar']);
    Route::put('account/password', [AccountController::class, 'update_password']);

    // profile
    // Route::get('profile', [ProfileController::class, 'index']);
    // Route::put('profile', [ProfileController::class, 'update']);
    // Route::put('profile/avatar', [ProfileController::class, 'update_avatar']);
    // Route::put('profile/password', [ProfileController::class, 'update_password']);
});

Route::group(['prefix' => 'master', 'middleware' => ['auth']], function () {

    // Jurusan
    Route::resource('jurusan', JurusanController::class)->parameter('jurusan', 'id');
    Route::post('jurusan/list', [JurusanController::class, 'list']);
    Route::get('jurusan/{id}/delete', [JurusanController::class, 'confirm']);

    // Prodi
    Route::resource('prodi', ProdiController::class)->parameter('prodi', 'id');
    Route::post('prodi/list', [ProdiController::class, 'list']);
    Route::get('prodi/{id}/delete', [ProdiController::class, 'confirm']);
});

Route::group(['prefix' => 'setting', 'middleware' => ['auth']], function () {

    // Menu
    Route::resource('menu', MenuController::class)->parameter('menu', 'id');
    Route::post('menu/list', [MenuController::class, 'list']);
    Route::get('menu/{id}/delete', [MenuController::class, 'confirm']);

    // Group
    Route::resource('group', GroupController::class)->parameter('group', 'id');
    Route::post('group/list', [GroupController::class, 'list']);
    Route::put('group/{id}/menu', [GroupController::class, 'menu_save']);
    Route::get('group/{id}/delete', [GroupController::class, 'confirm']);

    // User
    Route::resource('user', UserController::class)->parameter('user', 'id');
    Route::post('user/list', [UserController::class, 'list']);
    Route::get('user/{id}/delete', [UserController::class, 'confirm']);
});
