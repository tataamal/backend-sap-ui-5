<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductionOrderController;



Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
    ]);
});

Route::get('/sap-config-test', function () {
    return response()->json([
        'success' => true,
        'data' => [
            'ashost' => config('sap.ashost'),
            'sysnr' => config('sap.sysnr'),
            'client' => config('sap.client'),
            'user' => config('sap.user'),
            'lang' => config('sap.lang'),
        ],
    ]);
});



Route::get('/production-orders', [ProductionOrderController::class, 'index']);