<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\orderController;
use App\Http\Controllers\medicineController;
use App\Http\Controllers\doctorController;
use App\Http\Controllers\pharmacyController;
use App\Http\Controllers\userController;
use App\Http\Controllers\areaController;
use App\Http\Controllers\Api\VerifyEmailController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\medicine;

/*
|--------------------------------------------------------------------------
| Web Routes
p|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes();
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
//github login
Route::get('/auth/git/redirect', [RegisterController::class,'gitRedirect'])->name('gitRedirect');
Route::get('/auth/git/callback', [RegisterController::class,'gitCallBack'])->name('gitCallBack');
//twitter login
Route::get('/auth/twitter/redirect', [RegisterController::class,'twitterRedirect'])->name('twitterRedirect');
Route::get('/auth/twitter/callback', [RegisterController::class,'twitterCallBack'])->name('twitterCallBack');
Route::post('/webhook', [orderController::class, 'webhook'])->name('orders.webhook');
Route::get('/success', function () {return view('orders.success');})->name('orders.success');
Route::get('/cancel', function () {return view('orders.success');})->name('orders.cancel');
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['signed'])->name('verification.verify');
// Routes accessed by the three roles
Route::group(['middleware' => ['role:pharmacy|admin|doctor','auth']], function () {
    //Revenue
    Route::get('/', [App\Http\Controllers\RevenueController::class, 'RevenueCard'])->name('home');
    Route::get('/home', [App\Http\Controllers\RevenueController::class, 'RevenueCard']);
    //Orders
    Route::resource('/orders', orderController::class)->only('index', 'show', 'update');
    Route::post('/medicines', [medicineController::class,'store'])->name('medicines.store');
});
// Routes accessed by pharmacy and admin only
Route::group(['middleware' => ['role:pharmacy|admin','auth']], function () {

    //Doctors
    Route::resource('/doctors', doctorController::class)->only('index', 'show', 'store', 'update');
    Route::delete('/doctors/{doctor}', [doctorController::class,'delete'])->name('doctors.delete');
    Route::post('/bandoctor', [doctorController::class, 'ban'])->name('doctors.ban');


});

//Routes accessed by admin role only
Route::group(['middleware' => ['role:admin','auth']], function () {
    //Pharmacies
    Route::resource('/pharmacies', pharmacyController::class)->only('index', 'show', 'store', 'update');
    Route::delete('/pharmacies/{pharmacy}', [pharmacyController::class,'delete']);

    //users
    Route::resource('/users', userController::class)->only('index', 'show', 'store', 'update');
    Route::delete('/users/{user}', [userController::class,'delete'])->name('users.delete');

    //Areas
    Route::resource('/areas', areaController::class)->only('index', 'show', 'store', 'update');
    Route::delete('/areas/{area}', [areaController::class,'delete'])->name('areas.delete');

});
