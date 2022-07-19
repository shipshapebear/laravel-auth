<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ApplicationsController;
 
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

Route::post('forgot-password', [PassportAuthController::class, 'forgotPassword']);
Route::post('reset-password', [PassportAuthController::class, 'resetPassword']);
Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::put('update-status/{id}', [PassportAuthController::class, 'updateUserStatus']);
Route::put('update-role/{id}', [PassportAuthController::class, 'updateUserRole']);
//updateUserAccount with data request
Route::put('update-account/{id}', [PassportAuthController::class, 'updateUserAccount']);
Route::put('remove-owner/{id}', [PassportAuthController::class, 'removeOwner']);

Route::get('checkout', [PassportAuthController::class, 'checkout']);
//getall properties 
Route::get('properties', [PassportAuthController::class, 'getProperties']);


Route::post('apply', [ApplicationsController::class, 'apply']);



//sign-up as taxpayer 

Route::post('sign-up', [PassportAuthController::class, 'signup']);
Route::middleware('auth:api')->group(function () {
    Route::get('get-user', [PassportAuthController::class, 'userInfo']);
    Route::get('logout', [PassportAuthController::class, 'logout']);
    Route::get('accounts', [PassportAuthController::class, 'getUsers']);
    Route::delete('delete/{id}', [PassportAuthController::class, 'deleteUser']);
    Route::put('update-owner/{id}', [PassportAuthController::class, 'updateOwner']);
    Route::get('properties', [PassportAuthController::class, 'getUserProperties']);
    Route::get('property/{id}', [PassportAuthController::class, 'getProperty']);
});