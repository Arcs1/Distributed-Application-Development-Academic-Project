<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\StatisticsController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('user/register', [UserController::class, 'register']);
Route::post('orders/create', [OrderController::class, 'store']);
//Auth
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('users/me', [UserController::class, 'show_me']);

    //USER
    Route::get('users', [UserController::class, 'index']);
    Route::post('user/create', [UserController::class, 'store']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);
    Route::put('users/{user}/block', [UserController::class, 'handleBlock']);
    Route::get('users/{id}', [UserController::class, 'find']);
    Route::post('users/{id}/update', [UserController::class, 'update']);
    // ->middleware('can:update,user');
    Route::delete('users/{user}/photo', [UserController::class, 'destroy_photo']);
    Route::post('users/{id}/updatePassword', [UserController::class, 'changePassword']);

    //CUSTOMER
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/me', [CustomerController::class, 'show_me']);
    Route::post('customers/{id}/update', [CustomerController::class, 'update']);

    //ORDERS
    Route::get('orders', [OrderController::class, 'index']);
    Route::delete('orders/{order}', [OrderController::class, 'destroy']);
    Route::get('orders/unprepared', [OrderController::class, 'unpreparedOrders']);
    Route::get('orders/undelivered', [OrderController::class, 'undeliveredOrders']);
    Route::put('orders/{order}/prepare', [OrderController::class, 'prepareOrder']);
    Route::put('orders/{order}/deliver', [OrderController::class, 'deliverOrder']);
    Route::get('orders/customers/{id}',[OrderController::class, 'userOrders']);

    //ORDER_ITEMS
    Route::get('order_items', [OrderItemController::class, 'index']);
    Route::delete('order_items/{orderItem}', [OrderItemController::class, 'destroy']);
    Route::get('order_items/unprepared', [OrderItemController::class, 'unpreparedItems']);
    Route::get('order_items/waiting', [OrderItemController::class, 'waitingItems']);
    Route::put('order_items/{orderItem}/prepare', [OrderItemController::class, 'prepareItem']);
    Route::put('order_items/{orderItem}/ready', [OrderItemController::class, 'readyItem']);
    Route::get('order_items/{id}', [OrderItemController::class, 'orderItems']);
    Route::post('order_items/create', [OrderItemController::class, 'store']);
    Route::get('order_items/customers/{id}/mostFrequent',[OrderItemController::class,'mostFrequentCustomerItem']);

    //PRODUCTS
    Route::post('products/{id}/update', [ProductController::class, 'update']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
    Route::get('paginatedProducts', [ProductController::class, 'paginate']);
    Route::post('product/create', [ProductController::class, 'store']);
    Route::get('products/{id}', [ProductController::class, 'find']);

    //STATISTICS
    Route::get('statistics/ordersPerMonth/{year}',[StatisticsController::class,'ordersPorMes']);
    Route::get('statistics/salesPerMonth/{year}',[StatisticsController::class,'faturacaoPorMes']);
    Route::get('bestCustomer',[StatisticsController::class,'bestCustomer']);
    Route::get('mostRequestedItems',[StatisticsController::class,'mostRequestedItems']);
    Route::get('totalOrders',[StatisticsController::class,'totalOrders']);
    Route::get('totalCancelledOrders',[StatisticsController::class,'totalCancelledOrders']);
    Route::get('totalSales',[StatisticsController::class,'totalSales']);
    Route::get('statistics/usersPerMonth/{year}',[StatisticsController::class,'customersPorMes']);
});
Route::get('orders/ready', [OrderController::class, 'readyOrders']);
Route::get('products', [ProductController::class, 'index']);
