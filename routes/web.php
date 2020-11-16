<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AppointmentsController;

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


Auth::routes();
Route::get('/', function () {
    return view('welcome');
});
Route::get('/register/user', [RegisterController::class, 'showUserRegisterForm']);
Route::get('/register/physician', [RegisterController::class,'showPhysicianRegisterForm']);

Route::post('/register/user', [RegisterController::class, 'createUser']);
Route::post('/register/physician', [RegisterController::class,'createPhysician']);

Route::group(['middleware' => ['admin']], function () {
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
});
Route::group(['middleware' => ['physician']], function () {
    Route::get('/physician/home', [HomeController::class, 'physicianHome'])->name('physician.home');
});
Route::group(['middleware' => ['user']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/appointments/create', [AppointmentsController::class,'create']);
});
