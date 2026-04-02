<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Outstanding_SO1;
use App\Models\Outstanding_SO2;
use Illuminate\Http\JsonResponse;

class LocalSalesDataController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        // 1. AUTENTIKASI: Menggunakan Secret Key dari Header
        $secretKey = config('services.local_api.secret_key', 'Kmi3Seamrang123'); // 32 characters expected
        $providedKey = $request->header('X-API-KEY') ?? $request->query('api_key');

        if (!$providedKey || $providedKey !== $secretKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Secret key is missing or invalid.'
            ], 401);
        }

        $werks = $request->query('iv_werks');
        $auart = $request->query('iv_auart');

        // Validasi kombinasi parameter yang diperbolehkan
        $isValid = ($werks === '3000' && $auart === 'ZOR2') || ($werks === '2000' && $auart === 'ZOR1');

        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter iv_werks dan iv_auart tidak valid atau tidak diizinkan. Hanya (3000, ZOR2) atau (2000, ZOR1) yang diperbolehkan.'
            ], 403);
        }

        // Pengecualian pengembalian kolom (dihilangkan dari response)
        $hiddenColumns = ['mandt', 'auart2', 'qtys', 'ebdin', 'machp', 'type1', 'type2'];

        $tdata1 = Outstanding_SO1::where('werks', $werks)
            ->where('auart', $auart)
            ->get()
            ->makeHidden($hiddenColumns);

        $tdata2 = Outstanding_SO2::where('werks', $werks)
            ->where('auart', $auart)
            ->get()
            ->makeHidden($hiddenColumns);

        // 2. ENKRIPSI: Mengenkripsi paket data agar aman
        $payload = json_encode([
            't_data1' => $tdata1,
            't_data2' => $tdata2,
        ]);

        // Menggunakan AES-256-CBC untuk enkripsi
        $cipher = 'aes-256-cbc';
        // Memastikan key berukuran 32 bytes
        $key = substr(hash('sha256', $secretKey, true), 0, 32);
        // Generate random initialization vector (IV) - 16 bytes
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        
        $encryptedDatas = openssl_encrypt($payload, $cipher, $key, 0, $iv);
        
        // Response dikemas dalam Base64 format: base64(IV::EncryptedData)
        // Sehingga pihak client yang tahu SecretKey bisa mendekripsinya.
        $encryptedPackage = base64_encode($iv . $encryptedDatas);

        return response()->json([
            'success' => true,
            'message' => 'Data successfully retrieved and encrypted.',
            'encrypted_data' => $encryptedPackage
        ]);
    }
}
