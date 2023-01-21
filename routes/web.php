<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;

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

Route::get('/stripe', [UserController::class, 'redirectToStripe'])->name('redirect.stripe');
Route::post('/stripe', [UserController::class, 'redirectToStripe'])->name('redirect.stripe');
Route::get('/connect/{token}', [UserController::class, 'saveStripeAccount'])->name('save.stripe');

Route::post('/buy/{service}', [UserController::class, 'stripe'])->name('stripe');
Route::get('/buy/{service}', [UserController::class, 'stripe'])->name('stripe');
Route::post('/purchase/{service}', [UserController::class, 'purchase'])->name('complete.purchase');
Route::get('/purchase/{service}', [UserController::class, 'purchase'])->name('complete.purchase');

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('area/{area}', [HomeController::class, 'area'])->name('search.area');

Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::post('/ownedServices', [UserController::class, 'showServices'])->name('show.ownedServices');
Route::get('/becomeProvider', [UserController::class, 'becomeProvider']);

Route::post('save', [ServiceController::class, 'store'])->name('save.service');
Route::get('show/{service}', [ServiceController::class, 'show'])->name('show.service');
Route::post('delete/{service}', [ServiceController::class, 'delete'])->name('delete.service');
Route::post('/createService', [ServiceController::class, 'createService'])->name('create.service');
Route::get('/createService', [ServiceController::class, 'createService'])->name('create.service');

Route::get('edit/profile', [UserController::class, 'editProfile']);
Route::post('/profile/upload', [UserController::class, 'store']);
Route::post('/profile/update', [UserController::class, 'update']);
Route::post('/profile/edit', [UserController::class, 'update'])->name('edit.profile');
Route::post('/profile/destroy', [UserController::class, 'destroy'])->name('destroy.profile');

Route::get('user/{id}', [UserController::class, 'userProfile'])->name('show.profile');

