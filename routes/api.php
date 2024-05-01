<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientFlagController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryOptionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ExtraIngredientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPaymentController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\PointCardController;
use App\Http\Controllers\PointCardHistoryController;
use App\Http\Controllers\PointCardTypeController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SubCategoryController;
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

Route::get('/point', [PointController::class, 'index']);
Route::get('/point/change-operation-type', [PointController::class, 'changeOperationType']);
Route::get('/point/payment', [PointController::class, 'payment']);
Route::get('/point/stores', [PointController::class, 'getStores']);

Route::group(['prefix' => 'auth'], function(){
    $controller = AuthController::class;
    
    Route::post('/login', [$controller, 'login']);
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return response()->json([
            "data" => $request->user()
        ]);
    });
});

Route::group(['prefix' => 'customers'], function(){
    $controller = CustomerController::class;
    Route::get('/whatsapp', [$controller, 'getForWhatsapp']);

    Route::get('/{id}/add-point-card', [$controller, 'addPointCard']);


    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);

    Route::post('/{id}/addresses', [$controller, 'storeAddresses']);
    Route::put('/{id}/addresses/{address_id}', [$controller, 'updateAddress']);
    Route::delete('/{id}/addresses/{address_id}', [$controller, 'destroyAddress']);
});

Route::group(['prefix' => 'addresses'], function(){
    $controller = AddressController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'districts'], function(){
    $controller = DistrictController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'delivery-options'], function(){
    $controller = DeliveryOptionController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'payment-types'], function(){
    $controller = PaymentTypeController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'client-flags'], function(){
    $controller = ClientFlagController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'point-card-types'], function(){
    $controller = PointCardTypeController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'point-cards'], function(){
    $controller = PointCardController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);

    // Histories
    Route::get('{id}/histories', [$controller, 'indexHistories']);
});

Route::group(['prefix' => 'point-card-histories'], function(){
    $controller = PointCardHistoryController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'cash-registers'], function(){
    $controller = CashRegisterController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);

    Route::post('/{id}/histories', [$controller, 'histories']);
});

Route::group(['prefix' => 'categories'], function(){
    $controller = CategoryController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
    Route::get('/{id}/image', [$controller, 'getImage']);
});

Route::group(['prefix' => 'sub-categories'], function(){
    $controller = SubCategoryController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'sizes'], function(){
    $controller = SizeController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'products'], function(){
    $controller = ProductController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
    Route::get('/{id}/image', [$controller, 'getImage']);
});

Route::group(['prefix' => 'extra-ingredients'], function(){
    $controller = ExtraIngredientController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'roles'], function(){
    $controller = RoleController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'coupons'], function(){
    $controller = CouponController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});

Route::group(['prefix' => 'orders'], function(){
    $controller = OrderController::class;
    // Route::get('/analisis', [$controller, 'analisis']);

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
    Route::put('/{id}/status/{newStatus}', [$controller, 'updateOrderStatus']);

    Route::post('/{id}/payment', [$controller, 'payment']);
});

Route::group(['prefix' => 'order-payments'], function(){
    $controller = OrderPaymentController::class;

    Route::get('/', [$controller, 'index']);
    Route::post('/', [$controller, 'store']);
    Route::get('/{id}', [$controller, 'show']);
    Route::put('/{id}', [$controller, 'update']);
    Route::delete('/{id}', [$controller, 'destroy']);
});