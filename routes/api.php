<?php

use App\Http\Controllers\api\addressController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\orderController;
use App\Http\Controllers\api\VerifyEmailController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
//
Auth::routes(['verify' => true]);
Route::resource('/user', UserController::class)->only('store');
Route::post('/login', [AuthController::class, 'login']);

//Authenticated Only Users
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/email/verification-notification', [VerifyEmailController::class, 'resendNotification'])
    ->name('verification.send');
});
//Authenticated + Verified Users
Route::group(['middleware' => ['auth:sanctum','verified']], function () {
    Route::put('/editProfile', [ UserController::class,'update'])->name('users.update');


    Route::resource('/order', orderController::class)->only('index', 'store', 'update');
    Route::post('/cancelorder/{order}', [orderController::class, 'cancel']);
    Route::resource('/address', addressController::class)->only('index', 'show', 'store', 'update', 'destroy');

});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
