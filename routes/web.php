<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SubCategoryController;
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
Route::get('/vendor/login', [VendorController::class, 'VendorLogin'])->name('vendor.login');
Route::get('/become/vendor', [VendorController::class, 'BecomeVendor'])->name('become.vendor');
Route::post('/vendor/register', [VendorController::class, 'VendorRegister'])->name('vendor.register');




Route::middleware(['auth'])->group(function() {

    //brandcontroller
Route::controller(BrandController::class)->group(function (){
    Route::get('/all/brand','allBrand')->name('all.brand');
    Route::get('/add/brand','addBrand')->name('add.brand');
    Route::post('/store/brand','storeBrand')->name('store.brand');
    Route::get('/edit/brand{id}','editBrand')->name('edit.brand');
    Route::post('/update/brand','updateBrand')->name('update.brand');
    Route::get('/delete/brand{id}','deleteBrand')->name('delete.brand');


});

});//endmiddleware


Route::middleware(['auth'])->group(function() {

    //category controller
    Route::controller(CategoryController::class)->group(function (){
        Route::get('/all/category','allCategory')->name('all.category');
        Route::get('/add/category','addCategory')->name('add.category');
        Route::post('/store/category','storeCategory')->name('store.category');
        Route::get('/edit/category{id}','editCategory')->name('edit.category');
        Route::post('/update/category','updateCategory')->name('update.category');
        Route::get('/delete/category{id}','deleteCategory')->name('delete.category');

        //sub Category
        Route::controller(SubCategoryController::class)->group(function(){
            Route::get('/all/subcategory' , 'allSubCategory')->name('all.subcategory');
            Route::get('/add/subcategory' , 'addSubCategory')->name('add.subcategory');
            Route::post('/store/subcategory' , 'storeSubCategory')->name('store.subcategory');
            Route::get('/edit/subcategory/{id}' , 'editSubCategory')->name('edit.subcategory');
            Route::post('/update/subcategory' , 'updateSubCategory')->name('update.subcategory');
            Route::get('/delete/subcategory/{id}' , 'deleteSubCategory')->name('delete.subcategory');
            Route::get('/subcategory/ajax/{category_id}','getSubCategory');

        });


    });

    // Vendor Active and Inactive All Route
    Route::controller(AdminController::class)->group(function(){
        Route::get('/inactive/vendor' , 'InactiveVendor')->name('inactive.vendor');
        Route::get('/active/vendor' , 'ActiveVendor')->name('active.vendor');
        Route::get('/inactive/vendor/details/{id}' , 'InactiveVendorDetails')->name('inactive.vendor.details');
        Route::post('/active/vendor/approve' , 'ActiveVendorApprove')->name('active.vendor.approve');
        Route::get('/active/vendor/details/{id}' , 'ActiveVendorDetails')->name('active.vendor.details');
        Route::post('/inactive/vendor/approve' , 'InActiveVendorApprove')->name('inactive.vendor.approve');




    });


    // Product All Route
    Route::controller(ProductController::class)->group(function(){
        Route::get('/all/product' , 'allProduct')->name('all.product');
        Route::get('/add/product' , 'addProduct')->name('add.product');
        Route::post('/store/product' , 'storeProduct')->name('store.product');



    });

});//endmiddleware


