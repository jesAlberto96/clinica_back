<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/auth', [App\Http\Controllers\Api\V1\LoginController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/patients', [App\Http\Controllers\Api\V1\PatientController::class, 'index']);
    Route::post('/patients', [App\Http\Controllers\Api\V1\PatientController::class, 'store']);
    Route::get('/patients/{id}', [App\Http\Controllers\Api\V1\PatientController::class, 'show']);
    Route::delete('/patients/{id}', [App\Http\Controllers\Api\V1\PatientController::class, 'destroy']);
});