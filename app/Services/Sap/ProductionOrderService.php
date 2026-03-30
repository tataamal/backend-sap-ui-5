<?php

namespace App\Services\Sap;

class ProductionOrderService
{
    public function __construct(
        protected FlaskSapClient $flaskSapClient
    ) {
    }

    public function getByPlant(string $werks, ?string $aufnr = null): array
    {
        $result = $this->flaskSapClient->getProductionOrders($werks, $aufnr);

        return $result['t_data1'] ?? [];
    }
}