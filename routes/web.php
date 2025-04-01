<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\User\HomeController;

Route::get('/index', [HomeController::class, 'index'])->name('home');

use App\Http\Controllers\RoomController;

Route::get('/rooms', [RoomController::class, 'index']);

//use App\Http\Controllers\BookingController;
//
//Route::get('/fill_info', [BookingController::class, 'showForm'])->name('fill_info');

use App\Http\Controllers\User\CartController;

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

//--------------Tìm kiếm phòng--------------------------
use App\Http\Controllers\SearchController;

Route::get('/search-room', [SearchController::class, 'showForm'])->name('searchroom.form');
Route::post('/search-room', [SearchController::class, 'searchRoom'])->name('searchroom.search');
Route::get('/booking-information', [SearchController::class, 'hienThiThongTin'])->name('thongtin');

// ----------------Giỏ hàng-----------------------
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/remove/{room_id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/checkout/{room_id}', [CartController::class, 'checkout'])->name('cart.checkout');


// Admin


Route::prefix('admins')
    ->as('admin.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('customers', \App\Http\Controllers\Admin\CustomerManagementController::class);
        Route::resource('rooms', \App\Http\Controllers\Admin\RoomManagementController::class);
        Route::resource('bookings', \App\Http\Controllers\Admin\BookingManagementController::class);
    });


