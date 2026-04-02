<?php

// php artisan sap:fetch-sales-orders
// * * * * * cd /server-utama/app/backend-sap-ui-5 && php artisan schedule:run >> /dev/null 2>&1

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Services\Sap\ProductionOrderService;
use App\Models\Outstanding_SO1;
use App\Models\Outstanding_SO2;
use App\Models\Outstanding_SO3;
use App\Models\Outstanding_SO4;
use Illuminate\Support\Facades\DB;

#[Signature('sap:fetch-sales-orders')]
#[Description('Fetch Sales Orders from SAP and save to local database')]
class FetchSapSalesOrders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ProductionOrderService $productionOrderService)
    {
        // Mengizinkan script berjalan maksimum 6 jam (21600 detik)
        set_time_limit(21600);

        $this->info("Fetching Sales Orders from SAP...");

        $params = [
            ['werks' => '3000', 'auart' => 'ZOR2'],
            ['werks' => '2000', 'auart' => 'ZOR1'],
        ];

        $mergedResult = [
            't_data1' => [],
            't_data2' => [],
            't_data3' => [],
            't_data4' => [],
        ];

        foreach ($params as $param) {
            $this->info("Fetching for werks={$param['werks']}, auart={$param['auart']}...");
            
            try {
                $result = $productionOrderService->getBySalesOrder(
                    $param['auart'],
                    $param['werks']
                );

                if (isset($result['t_data1'])) $mergedResult['t_data1'] = array_merge($mergedResult['t_data1'], $result['t_data1']);
                if (isset($result['t_data2'])) $mergedResult['t_data2'] = array_merge($mergedResult['t_data2'], $result['t_data2']);
                if (isset($result['t_data3'])) $mergedResult['t_data3'] = array_merge($mergedResult['t_data3'], $result['t_data3']);
                if (isset($result['t_data4'])) $mergedResult['t_data4'] = array_merge($mergedResult['t_data4'], $result['t_data4']);
                
                $this->info("Successfully fetched data for werks={$param['werks']}, auart={$param['auart']}.");
            } catch (\Exception $e) {
                $this->error("Failed to fetch for werks={$param['werks']}, auart={$param['auart']}. Error: " . $e->getMessage());
                return;
            }
        }

        $this->info("Saving to database...");

        DB::beginTransaction();
        try {
            Outstanding_SO1::query()->delete();
            Outstanding_SO2::query()->delete();
            Outstanding_SO3::query()->delete();
            Outstanding_SO4::query()->delete();

            $now = now();

            $modelsMap = [
                't_data1' => Outstanding_SO1::class,
                't_data2' => Outstanding_SO2::class,
                't_data3' => Outstanding_SO3::class,
                't_data4' => Outstanding_SO4::class,
            ];

            foreach ($modelsMap as $key => $modelClass) {
                if (!empty($mergedResult[$key])) {
                    $insertData = array_map(function ($item) use ($now) {
                        $mapped = array_change_key_case($item, CASE_LOWER);
                        $mapped['created_at'] = $now;
                        $mapped['updated_at'] = $now;

                        foreach ($mapped as $col => $val) {
                            if (is_string($val) && trim($val) === '') {
                                $mapped[$col] = null;
                            } elseif ($val === '00000000') {
                                $mapped[$col] = null;
                            }
                        }

                        return $mapped;
                    }, $mergedResult[$key]);

                    foreach (array_chunk($insertData, 500) as $chunk) {
                        $modelClass::insert($chunk);
                    }
                }
            }

            DB::commit();
            $this->info("Successfully saved Sales Orders to database.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Failed to save data: " . $e->getMessage());
        }
    }
}
