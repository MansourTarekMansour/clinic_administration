<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;

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
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
