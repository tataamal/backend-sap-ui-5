<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductionOrderRequest;
use App\Services\Sap\ProductionOrderService;
use Illuminate\Http\JsonResponse;

class ProductionOrderController extends Controller
{
    public function index(
        ProductionOrderRequest $request,
        ProductionOrderService $service
    ): JsonResponse {
        $data = $service->getByPlant(
            $request->input('p_werks'),
            $request->input('p_aufnr')
        );

        return response()->json([
            'success' => true,
            'message' => 'Production order fetched successfully',
            'data' => $data,
        ]);
    }
}