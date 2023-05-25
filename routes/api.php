<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientVisitController;
use App\Http\Controllers\TreatmentPlanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    // Register user
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    // Login user
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    // Logout user
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
    // Reset password
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');
    });
});

Route::group(['prefix' => 'patients'], function () {
    Route::get('/', [PatientController::class, 'index']);
    Route::get('/id', [PatientController::class, 'showId']);
    Route::get('/name', [PatientController::class, 'showName']);
    Route::get('/date', [PatientController::class, 'showDate']);
    Route::post('/store', [PatientController::class, 'store']);
    Route::put('/update/{id}', [PatientController::class, 'update']);
    Route::delete('/delete', [PatientController::class, 'destroy']);
});

Route::group(['prefix' => 'patients/visits'], function () {
    Route::get('/', [PatientVisitController::class, 'index']);
    Route::get('/id', [PatientVisitController::class, 'showId']);
    Route::get('/patientid', [PatientVisitController::class, 'showByPatientId']);
    Route::get('/name', [PatientVisitController::class, 'showName']);
    Route::get('/date', [PatientVisitController::class, 'showDate']);
    Route::post('/store/{patientId}', [PatientVisitController::class, 'store']);
    Route::put('/update/{id}', [PatientVisitController::class, 'update']);
    Route::delete('/delete', [PatientVisitController::class, 'destroy']);
});

Route::group(['prefix' => 'treatment-plans'], function () {
    Route::get('/', [TreatmentPlanController::class, 'index']);
    Route::get('/{id}', [TreatmentPlanController::class, 'show']);
    Route::post('/', [TreatmentPlanController::class, 'store']);
    Route::put('/update/{id}', [TreatmentPlanController::class, 'update']);
    Route::delete('/delete/{id}', [TreatmentPlanController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
