<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\HomeController;
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

Route::get('/',  [HomeController::class, 'index']);


Route::get('/index', [HomeController::class, 'index'])->name('home');

Route::get('/rooms', [RoomController::class, 'index']);

//use App\Http\Controllers\BookingController;
//
//Route::get('/fill_info', [BookingController::class, 'showForm'])->name('fill_info');

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

//--------------Tìm kiếm phòng--------------------------

Route::get('/search-room', [SearchController::class, 'showForm'])->name('searchroom.form');
Route::post('/search-room', [SearchController::class, 'searchRoom'])->name('searchroom.search');
Route::get('/booking-information', [SearchController::class, 'hienThiThongTin'])->name('thongtin');

// ----------------Giỏ hàng-----------------------
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/remove/{room_id}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/checkout/{room_id}', [CartController::class, 'checkout'])->name('cart.checkout');
//-----------ĐIỀN THÔNG TIN-----------------//

Route::get('/thong-tin-dat-phong', [BookingController::class, 'showBooking'])->name('showBooking');
Route::get('/xoa-phong/{index}', [BookingController::class, 'xoaPhong'])->name('xoaPhong');
Route::post('/save-customer-info', [BookingController::class, 'saveCustomerInfo'])->name('saveCustomerInfo');

//---------Thanh toán-----------//
Route::get('/payment', [BookingController::class, 'showPaymentPage'])->name('paymentPage');



//---------Thanh toán-----------//

Route::post('/payment', [BookingController::class, 'payment'])->name('payment');
Route::get('/payment/callBack', [BookingController::class, 'paymentCallback'])->name('payment.callback');

//Auth

Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'showFormLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');

Route::get('/register', [\App\Http\Controllers\Auth\AuthController::class, 'showFormRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register'])->name('register');

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
    });


