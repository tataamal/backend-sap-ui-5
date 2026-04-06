<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesOrderRequest;
use App\Services\Sap\ProductionOrderService;
use App\Models\Outstanding_SO1;
use App\Models\Outstanding_SO2;
use App\Models\Outstanding_SO3;
use App\Models\Outstanding_SO4;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    public function __construct(
        protected ProductionOrderService $productionOrderService
    ) {
    }

    public function __invoke(SalesOrderRequest $request): JsonResponse
    {
        $result = $this->productionOrderService->getBySalesOrder(
            $request->input('iv_auart'),
            $request->input('iv_werks'),
            $request->input('iv_balance')
        );

        DB::beginTransaction();
        try {
            $iv_auart = $request->input('iv_auart');
            $iv_werks = $request->input('iv_werks');

            // Hapus data lama sesuai parameter sebelum insert data baru (agar tidak duplikat)
            Outstanding_SO1::where('auart', $iv_auart)->where('werks', $iv_werks)->delete();
            Outstanding_SO2::where('auart', $iv_auart)->where('werks', $iv_werks)->delete();
            Outstanding_SO3::where('auart', $iv_auart)->where('werks', $iv_werks)->delete();
            Outstanding_SO4::where('auart', $iv_auart)->where('werks', $iv_werks)->delete();

            $now = now();

            $modelsMap = [
                't_data1' => Outstanding_SO1::class,
                't_data2' => Outstanding_SO2::class,
                't_data3' => Outstanding_SO3::class,
                't_data4' => Outstanding_SO4::class,
            ];

            foreach ($modelsMap as $key => $modelClass) {
                if (!empty($result[$key])) {
                    // Konversi key uppercase dari SAP menjadi lowercase sesuai kolom database
                    $insertData = array_map(function ($item) use ($now) {
                        $mapped = array_change_key_case($item, CASE_LOWER);
                        $mapped['created_at'] = $now;
                        $mapped['updated_at'] = $now;

                        // Konversi string kosong dan blank date SAP menjadi null
                        foreach ($mapped as $col => $val) {
                            if (is_string($val) && trim($val) === '') {
                                $mapped[$col] = null;
                            } elseif ($val === '00000000') {
                                $mapped[$col] = null;
                            }
                        }

                        return $mapped;
                    }, $result[$key]);

                    // Insert dalam chunk agar aman jika data ribuan
                    foreach (array_chunk($insertData, 500) as $chunk) {
                        $modelClass::insert($chunk);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data dari SAP sudah berhasil ditarik dan disimpan.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
