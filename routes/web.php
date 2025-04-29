<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DailyController;
use App\Http\Controllers\DailyUserController;
use App\Http\Controllers\DemoDailyController;
use App\Http\Controllers\ExportFPDFController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MultipleImageController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserViewsController;
use App\Http\Controllers\ProgramPengembangan;
use App\Http\Controllers\RenstraController;
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
Route::get('/profile',[LayoutController::class,'profile'])->middleware('auth');
Route::post('/profile/update', [LayoutController::class, 'updateProfile'])->name('profile.update')->middleware('auth');
Route::post('/profile/change-password', [LayoutController::class, 'changePassword'])->name('profile.change-password')->middleware('auth');
Route::get('/filter-daily', [LayoutController::class, 'filterDaily'])->name('filter.daily');
Route::get('/filter-admin', [LayoutController::class, 'filterAdmin'])->name('filter.admin');

Route::get('/notifications/read/{id}', [LayoutController::class, 'markAsRead'])->name('notifications.read');

// Punya Kita
Route::controller(LoginController::class)->group(function () {
    Route::get('login','index')->name('login');
    Route::post('login/proses','proses');
    Route::get('logout','logout');
});

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

    Route::get('/setup-password', [SocialiteController::class, 'setupPasswordForm'])
    ->name('setup-password.form')->middleware('auth');

Route::post('/setup-password', [SocialiteController::class, 'setupPassword'])
    ->name('setup-password.submit')->middleware('auth');





Route::group(['middleware' => ['auth']],function(){
    // Level 1 Untuk Super Admin
    Route::group(['middleware' => ['AksesRole:1']],function(){
        Route::get('/user-export', [UserController::class, 'export'])->name('user.export'); // untok spreedshet
        // Route::get('/user/export/pdf', [UserController::class, 'exportPdf'])->name('user.export.pdf'); //ini dom pdft
        Route::get('/user/pdf', [\App\Http\Controllers\UserController::class, 'exportPdf'])->name('user.pdf')->middleware('auth'); //Download pdf
        Route::get('/user/export-pdf', [UserController::class, 'exportPdf2'])->name('user.export.pdf'); // exportpdf
        Route::resource('/user', \App\Http\Controllers\UserController::class)->middleware('auth');
        Route::resource('/renstra', \App\Http\Controllers\RenstraController::class)->middleware('auth');
        Route::get('/renstra/detail/{id}', [RenstraController::class, 'show'])
        ->name('renstra.signed.detail')
        ->middleware('signed'); 
        Route::resource('/pilar', \App\Http\Controllers\PilarController::class)->middleware('auth');
        Route::resource('/isustrategis', \App\Http\Controllers\IsuStrategiController::class)->middleware('auth');
        Route::resource('/progpeng', \App\Http\Controllers\ProgramPengembanganController::class)->middleware('auth');
     
        Route::resource('/actv', \App\Http\Controllers\ActivityController::class)->middleware('auth');
        Route::put('/actv/{id}', [ActivityController::class, 'update'])->name('actv.update');
        Route::post('/actv/mass-laporkans', [\App\Http\Controllers\ActivityController::class, 'massReports'])->name('actv.massReports');
        Route::get('/actv/{id}/detail', [\App\Http\Controllers\DailyController::class, 'detail'])->name('actv.detail');
        Route::get('daprog', [\App\Http\Controllers\LayoutAdminController::class, 'daprog'])->name('daprog');
        Route::get('dadone', [\App\Http\Controllers\LayoutAdminController::class, 'dadone'])->name('dadone');
        Route::get('daall', [\App\Http\Controllers\LayoutAdminController::class, 'daall'])->name('daall');
        Route::get('actvprog', [\App\Http\Controllers\LayoutAdminController::class, 'actvprog'])->name('actvprog');
        Route::get('actvdone', [\App\Http\Controllers\LayoutAdminController::class, 'actvdone'])->name('actvdone');
        // User
        Route::get('users', [\App\Http\Controllers\LayoutAdminController::class, 'users'])->name('users');
        Route::get('users/create', [\App\Http\Controllers\LayoutAdminController::class, 'createUser'])->name('users.create');
        Route::post('users/store', [\App\Http\Controllers\LayoutAdminController::class, 'storeUser'])->name('users.store');

    });
});

    Route::group(['middleware' => ['AksesRole:2']],function(){
        Route::resource('/daily', \App\Http\Controllers\DailyUserController::class)->middleware('auth');
        Route::get('dailprog', [\App\Http\Controllers\LayoutUserController::class, 'dailprog'])->name('dailprog');
        Route::get('daildone', [\App\Http\Controllers\LayoutUserController::class, 'daildone'])->name('daildone');
        Route::put('/daily/{id}', [DailyUserController::class, 'update'])->name('daily.update');
        Route::post('/daily/mass-laporkan', [\App\Http\Controllers\DailyUserController::class, 'massReport'])->name('daily.massReport');
        Route::get('/daily/{id}/detail', [\App\Http\Controllers\DailyUserController::class, 'detail'])->name('daily.detail');
        Route::get('/chart-data-user', [LayoutController::class, 'getChartDataUser']);
        
    });