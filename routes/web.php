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

Route::get('/stripe/{id}', [UserController::class, 'redirectToStripe'])->name('redirect.stripe');
Route::post('/stripe/{id}', [UserController::class, 'redirectToStripe'])->name('redirect.stripe');
Route::get('/connect/{token}', [UserController::class, 'saveStripeAccount'])->name('save.stripe');

Route::post('/buy/{service}', [UserController::class, 'stripe'])->name('stripe');
Route::get('/buy/{service}', [UserController::class, 'stripe'])->name('stripe');
Route::post('/purchase/{service}', [UserController::class, 'purchase'])->name('complete.purchase');
Route::get('/purchase/{service}', [UserController::class, 'purchase'])->name('complete.purchase');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('area/{area}', [HomeController::class, 'area'])->name('search.area');

Route::get('/profile/{id}', [UserController::class, 'profile'])->name('profile');
Route::post('/ownedServices/{user}', [HomeController::class, 'showServices'])->name('show.ownedServices');
Route::post('/becomeProvider', [HomeController::class, 'becomeProvider'])->name('becomeProvider');

Route::post('save', [ServiceController::class, 'store'])->name('save.service');
Route::get('show/{service}', [ServiceController::class, 'show'])->name('show.service');
Route::post('delete/{service}', [ServiceController::class, 'delete'])->name('delete.service');
Route::post('/createService', [HomeController::class, 'createService'])->name('create.service');
Route::get('/createService', [HomeController::class, 'createService'])->name('create.service');

Route::get('edit/user', [HomeController::class, 'editUser']);
Route::post('/user/upload/{user}', [UserController::class, 'store']);
Route::post('/user/update/{user}', [UserController::class, 'update']);
Route::post('/user/edit/{user}', [UserController::class, 'update'])->name('edit.user');
Route::post('/user/destroy/{user}', [UserController::class, 'destroy'])->name('destroy.user');

Route::get('user/{id}', [HomeController::class, 'userProfile'])->name('show.profile');

