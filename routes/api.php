<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BillingController;
use App\Http\Controllers\Api\V1\UsageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::get('/ping', function() {
    return response()->json(['status' => 'success', 'message' => 'Pong!', 'time' => now()]);
});
Route::prefix('v1')->group(function () {
    // Add Usage - No Authentication
    Route::post('/usage', [UsageController::class, 'addUsage']);

    // Calculate Bill - With Authentication
    Route::middleware('auth:api')->post('/calculate-bill', [BillingController::class, 'calculateBill']);

    // Query Bill - No Authentication
    Route::get('/bill', [BillingController::class, 'queryBill']);

    // Query Bill Detailed - With Authentication and Pagination
    Route::middleware('auth:api')->get('/bill-detailed', [BillingController::class, 'queryBillDetailed']);

    // Pay Bill - No Authentication
    Route::post('/pay-bill', [BillingController::class, 'payBill']);
    
    // Auth routes
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
});