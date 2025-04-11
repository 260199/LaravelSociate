<?php

use App\Http\Controllers\ExportFPDFController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserViewsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[LayoutController::class,'index'])->middleware('auth');
Route::get('/home',[LayoutController::class,'index'])->middleware('auth');

// Punya Kita
Route::controller(LoginController::class)->group(function () {
    Route::get('login','index')->name('login');
    Route::post('login/proses','proses');
    Route::get('logout','logout');
});


// Google Clouud

// Untuk menampilkan halaman
// Route::get('/', function () {
//     return view('index');
// })->name('index');

// Untuk redirect ke Google
Route::get('login/google/redirect', [SocialiteController::class, 'redirect'])
    ->middleware(['guest'])
    ->name('redirect');

// Untuk callback dari Google
Route::get('login/google/callback', [SocialiteController::class, 'callback'])
    ->middleware(['guest'])
    ->name('callback');

// Untuk logout
Route::post('logout', [SocialiteController::class, 'logout'])
    ->middleware(['auth'])
    ->name('logout');




Route::group(['middleware' => ['auth']],function(){
    // Level 1 Untuk Super Admin
    Route::group(['middleware' => ['AksesRole:1']],function(){
        Route::get('/user-export', [UserController::class, 'export'])->name('user.export'); // untok spreedshet
        // Route::get('/user/export/pdf', [UserController::class, 'exportPdf'])->name('user.export.pdf'); //ini dom pdft
        Route::get('/user/pdf', [\App\Http\Controllers\UserController::class, 'exportPdf'])->name('user.pdf')->middleware('auth'); //Download pdf
        Route::get('/user/export-pdf', [UserController::class, 'exportPdf2'])->name('user.export.pdf'); // exportpdf
        Route::resource('/user', \App\Http\Controllers\UserController::class)->middleware('auth');
    });
});

    // Level 2 Untuk AO
    Route::group(['middleware' => ['AksesRole:2']],function(){
        Route::get('/userr', [UserViewsController::class, 'index']);
    });
