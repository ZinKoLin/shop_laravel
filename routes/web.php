<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('frontend.index');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function() {

    Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('dashboard');
    Route::post('/user/profile/store', [UserController::class, 'userProfileStore'])->name('user.profile.store');
    Route::get('/user/logout', [UserController::class, 'userLogout'])->name('user.logout');
    Route::post('/user/update/password', [UserController::class, 'userUpdatePassword'])->name('user.update.password');





});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


//admin dashboard
Route::middleware(['auth','role:admin'])->group(function (){
    Route::get('/admin/dashboard',[AdminController::class,'adminDashboard'])->name('#admin.dashboard');
    Route::get('/admin/logout',[AdminController::class,'adminDestroy'])->name('#admin.logout');
    Route::get('/admin/profile',[AdminController::class,'adminProfile'])->name('#admin.profile');
    Route::post('/admin/profile/store',[AdminController::class,'adminProfileStore'])->name('#admin.profile.store');
    Route::get('/admin/password/change',[AdminController::class,'adminChangPassword'])->name('#admin.change.password');
    Route::post('/admin/password/update',[AdminController::class,'adminUpdatePassword'])->name('#admin.update.password');








});

//vendor Dashboard
Route::middleware(['auth','role:vendor'])->group(function (){
    Route::get('/vendor/dashboard',[VendorController::class,'vendorDashboard'])->name('#vendor.dashboard');
    Route::get('/vendor/logout',[VendorController::class,'vendorDestroy'])->name('#vendor.logout');
    Route::get('/vendor/profile',[VendorController::class,'vendorProfile'])->name('#vendor.profile');
    Route::post('/vendor/profile/store',[VendorController::class,'vendorProfileStore'])->name('#vendor.profile.store');
    Route::get('/vendor/password/change',[VendorController::class,'vendorChangPassword'])->name('#vendor.change.password');
    Route::post('/vendor/password/update',[VendorController::class,'vendorUpdatePassword'])->name('#vendor.update.password');




});

Route::get('admin/login',[AdminController::class,'adminLogin']);
Route::get('vendor/login',[VendorController::class,'vendorLogin']);
