<?php

use App\Http\Controllers\AuthorController;
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

Route::prefix('author')->name('author.')->group(function(){
      
    Route::middleware(['guest:web'])->group(function(){
        Route::view('/login','back.pages.auth.login')->name('login');
        Route::view('/forgot-password','back.pages.auth.forgot')->name('forgot-password');
        Route::get('/password/reset/{token}',[AuthorController::class,'ResetForm'])->name('reset-form');
    });
    Route::middleware(['author:web'])->group(function(){
      Route::get('/home',[AuthorController::class,'index'])->name('home');
      Route::post('/logout', [AuthorController::class, 'logout'])->name('logout');
      Route::view('/profile','back.pages.profile')->name('profile');
      Route::post('/change-profile-picture',[AuthorController::class,'changeProfilePicture'])->name('change-profile-picture');
      Route::view('/setting','back.pages.settings')->name('settings');
      Route::post('/change-blog-logo',[AuthorController::class,'changeBlogLogo'])->name('change-blog-logo');
    });

    
});
