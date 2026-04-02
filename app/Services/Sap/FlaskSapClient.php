<?php

namespace App\Services\Sap;

use Exception;
use Illuminate\Support\Facades\Http;

class FlaskSapClient
{
    public function getProductionOrders(string $werks, ?string $aufnr = null): array
    {
        $query = [
            'p_werks' => $werks,
        ];

        if (!empty($aufnr)) {
            $query['p_aufnr'] = $aufnr;
        }

        $response = Http::timeout((int) config('services.sap_flask.timeout', 3600))
            ->acceptJson()
            ->withHeaders([
                'X-Internal-Token' => config('services.sap_flask.token'),
            ])
            ->get(
                rtrim(config('services.sap_flask.base_url'), '/') . '/rfc/production-orders',
                $query
            );

        if ($response->failed()) {
            throw new Exception(
                'Failed to call Flask SAP service: ' . $response->body()
            );
        }

        return $response->json();
    }

    public function getSalesOrders(string $auart, string $werks, ?string $balance = null): array
    {
        $query = [
            'iv_auart' => $auart,
            'iv_werks' => $werks,
        ];

        if (!empty($balance)) {
            $query['iv_balance'] = $balance;
        }

        $response = Http::timeout((int) config('services.sap_flask.timeout', 3600))
            ->acceptJson()
            ->withHeaders([
                'X-Internal-Token' => config('services.sap_flask.token'),
            ])
            ->get(
                rtrim(config('services.sap_flask.base_url'), '/') . '/rfc/sales-orders',
                $query
            );

        if ($response->failed()) {
            throw new Exception(
                'Failed to call Flask SAP service: ' . $response->body()
            );
        }

        return $response->json();
    }   
}