<?php

use App\Http\Controllers\Admin\BookingManagementController;
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

use App\Http\Controllers\User\CartController;
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
//--------------Tìm kiếm phòng--------------------------
use App\Http\Controllers\SearchController;
Route::get('/search-room', [SearchController::class, 'showForm'])->name('searchroom.form');
Route::post('/search-room', [SearchController::class, 'searchRoom'])->name('searchroom.search');
Route::post('/booking-information', [SearchController::class, 'hienThiThongTin'])->name('thongtin');

// ----------------Giỏ hàng-----------------------
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/remove/{room_id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/checkout/{room_id}', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/update/{index}', [CartController::class, 'Update'])->name('cart.update');
Route::get('/proceed-to-booking', [CartController::class, 'proceedToBooking'])->name('cart.proceedToBooking');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
//-----------ĐIỀN THÔNG TIN-----------------//
use App\Http\Controllers\BookingController;
Route::get('/thong-tin-dat-phong', [BookingController::class, 'showBooking'])->name('showBooking');
Route::get('/xoa-phong/{index}', [BookingController::class, 'xoaPhong'])->name('xoaPhong');
// Route::post('/save-customer-info', [BookingController::class, 'saveCustomerInfo'])->name('saveCustomerInfo');
Route::get('/payment', [BookingController::class, 'showPaymentPage'])->name('paymentPage');
//---------Thanh toán-----------//
//Route::get('/payment', [BookingController::class, 'showPaymentPage'])->name('paymentPage');
Route::post('/booking/submit', [BookingController::class, 'saveBookingWithCustomerInfo'])->name('saveBookingWithCustomerInfo');
Route::get('/booking/confirm', [BookingController::class, 'showConfirmPage'])->name('confirmBooking');
//Auth

Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'showFormLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');

//Route::get('/register', [\App\Http\Controllers\Auth\AuthController::class, 'showFormRegister'])->name('register');
//Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register'])->name('register');

Route::get('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

// Admin


Route::prefix('admins')
    ->middleware(['checkAdmin'])
    ->as('admin.')
    ->group(function () {

        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('customers', \App\Http\Controllers\Admin\CustomerManagementController::class);
        Route::resource('rooms', \App\Http\Controllers\Admin\RoomManagementController::class);
        Route::resource('bookings', \App\Http\Controllers\Admin\BookingManagementController::class);
        Route::post('bookings/{id}/update-rooms', [BookingManagementController::class, 'updateRooms'])->name('bookings.updateRooms');

    });
