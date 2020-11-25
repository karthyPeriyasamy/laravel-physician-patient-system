<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\API\SpecialistController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AppointmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::get('specialist', [SpecialistController::class, 'index']);
    Route::group(['prefix' => 'physician'], function () {
        Route::post('register', [RegisterController::class, 'createPhysician']);
    });
    Route::group(['prefix' => 'patient'], function () {
        Route::post('register', [RegisterController::class, 'createUser']);
    });
    Route::middleware(['auth:api', 'role'])->group(function () {
        Route::middleware(['scope:admin'])->group(function () {
            Route::group(['prefix' => 'admin'], function () {
                Route::post('specialist', [SpecialistController::class, 'create']);
                Route::put('specialist', [SpecialistController::class, 'update']);
                Route::get('users', [UserController::class, 'index']);
                Route::put('approve', [UserController::class, 'approved']);
            });
        });

        Route::middleware(['scope:physician'])->group(function () {
            Route::group(['prefix' => 'physician'], function () {
                Route::put('specialist', [SpecialistController::class, 'addToPhysician']);
                Route::delete('specialist', [SpecialistController::class, 'removeFromPhysician']);
                Route::get('appointment', [AppointmentController::class, 'physicianAppointments']);
                Route::put('appointment', [AppointmentController::class, 'updateStatus']);
            });
        });
        Route::middleware(['scope:user'])->group(function () {
            Route::group(['prefix' => 'patient'], function () {
                Route::get('appointment', [AppointmentController::class, 'patientAppointments']);
                Route::post('appointment', [AppointmentController::class, 'create']);
                Route::put('appointment', [AppointmentController::class, 'update']);
                Route::delete('appointment', [AppointmentController::class, 'delete']);
            });
        });
        Route::middleware(['scope:user,physician'])->group(function () {
            Route::group(['prefix' => 'appointment'], function () {
                Route::get('details', [AppointmentController::class, 'getAppointment']);
            });
        });
    });
});
Route::fallback(function () {
    return response()->json([
        'message' => Config::get('constants.api.page_not_found')], 404);
});
