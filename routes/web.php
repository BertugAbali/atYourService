<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StripePaymentController;

use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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


Auth::routes(['verify'=>true]);

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('area/{area}', [HomeController::class, 'area'])->name('search.area');

Route::get('profile/{id}', [HomeController::class, 'userProfile'])->name('show.profile');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('save', [ServiceController::class, 'store'])->name('create.service');

Route::get('/profile', [HomeController::class, 'profile'])->name('profile');


Route::post('/startNewService', [HomeController::class, 'startNewService'])->name('startNewService');
Route::get('/startNewService', [HomeController::class, 'startNewService'])->name('startNewService');

Route::post('/becomeProvider', [HomeController::class, 'becomeProvider'])->name('becomeProvider');


Route::get('show/{service}', [ServiceController::class, 'show'])->name('show.service');


Route::post('/user/update/{user}', [UserController::class, 'update']);
Route::post('/user/destroy/{user}', [UserController::class, 'destroy']);

// Route::post('/stripe/{service}', [StripePaymentController::class, 'stripePost'])->name('stripe.post');

Route::post('/stripe/{service}', [StripePaymentController::class, 'stripe'])->name('stripe');

Route::controller(StripePaymentController::class)->group(function(){
    Route::get('stripe', 'stripe');
    Route::post('stripe', 'stripePost')->name('stripe.post');

});

