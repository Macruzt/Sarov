<?php
// app/Services/FacturaSyncService.php

namespace App\Services;

use App\Models\Factura;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacturaSyncService
{
    private string $javaApiUrl;
    private bool $useJavaAPI;

    public function __construct()
    {
        $this->javaApiUrl = config('services.java_api.url', 'http://localhost:8081/api');
        $this->useJavaAPI = config('services.java_api.enabled', true);
    }

    /**
     * Obtener facturas desde Java API
     */
    public function getFacturasFromJava(array $params = []): ?array
    {
        if (!$this->useJavaAPI) {
            return null;
        }

        try {
            $defaultParams = [
                'page' => 0,
                'size' => 15,
                'sortBy' => 'fechaEmision',
                'sortDir' => 'desc'
            ];

            $requestParams = array_merge($defaultParams, array_filter($params));

            $response = Http::timeout(10)
                           ->get($this->javaApiUrl . '/facturas', $requestParams);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['success']) && $data['success']) {
                    return [
                        'success' => true,
                        'data' => $data['data'] ?? [],
                        'pagination' => [
                            'current_page' => ($data['current_page'] ?? 0) + 1,
                            'last_page' => $data['total_pages'] ?? 1,
                            'per_page' => $data['page_size'] ?? 15,
                            'total' => $data['total_items'] ?? 0,
                            'from' => (($data['current_page'] ?? 0) * ($data['page_size'] ?? 15)) + 1,
                            'to' => min((($data['current_page'] ?? 0) + 1) * ($data['page_size'] ?? 15), $data['total_items'] ?? 0)
                        ]
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Java API error in getFacturasFromJava: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Obtener una factura específica desde Java API
     */
    public function getFacturaFromJava($id): ?array
    {
        if (!$this->useJavaAPI) {
            return null;
        }

        try {
            $response = Http::timeout(5)->get($this->javaApiUrl . "/facturas/{$id}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['success']) && $data['success']) {
                    return [
                        'success' => true,
                        'data' => $this->mapJavaFacturaToLaravel($data['data'])
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Java API error in getFacturaFromJava: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Crear factura en Java API
     */
 public function createFacturaInJava(array $data): ?array
{
    if (!$this->useJavaAPI) {
        return null;
    }

    try {
        // Mapear con snake_case porque Java Controller usa @RequestBody Map
        $javaData = [
            'factura' => [
                'numero_factura' => $data['factura']['numero_factura'],
                'fecha_emision' => $data['factura']['fecha_emision'],
                'fecha_vencimiento' => $data['factura']['fecha_vencimiento'],
                'concepto_detalle' => $data['factura']['concepto_detalle'],
                'forma_pago' => $data['factura']['forma_pago'],
                'supplier_id' => $data['factura']['supplier_id'],
                'subtotal' => $data['factura']['subtotal'] ?? 0,
                'total_iva' => $data['factura']['total_iva'] ?? 0,
                'total' => $data['factura']['total'] ?? 0,
                'estado' => strtolower($data['factura']['estado'] ?? 'pendiente'),
                'user_id' => $data['factura']['user_id'] ?? 1
            ],
            'items' => []
        ];

        // Mapear items con snake_case
        foreach ($data['items'] as $item) {
            $itemData = [
                'detalle' => $item['detalle'],
                'unidad_medida' => $item['unidad_medida'],
                'cantidad' => (int)$item['cantidad'],
                'valor_unitario' => (float)$item['valor_unitario'],
                'porcentaje_iva' => (float)$item['porcentaje_iva']
            ];
            
            // CRÍTICO: Agregar product_id si existe
            if (isset($item['product_id']) && $item['product_id']) {
                $itemData['product_id'] = (int)$item['product_id'];
            }
            
            $javaData['items'][] = $itemData;
        }

        Log::info('Enviando a Java:', $javaData);

        $response = Http::timeout(10)
                       ->post($this->javaApiUrl . '/facturas', $javaData);

        if ($response->successful()) {
            $responseData = $response->json();
            
            if (isset($responseData['success']) && $responseData['success']) {
                return [
                    'success' => true,
                    'data' => $this->mapJavaFacturaToLaravel($responseData['data'])
                ];
            }
        }
        
        Log::warning('Java API response error: ' . $response->body());
        
    } catch (\Exception $e) {
        Log::warning('Java API error in createFacturaInJava: ' . $e->getMessage());
    }

    return null;
}

    /**
     * Actualizar factura en Java API
     */
    public function updateFacturaInJava($id, array $data): ?array
{
    if (!$this->useJavaAPI) {
        return null;
    }

    try {
        // Mapear con snake_case igual que en create
        $javaData = [
            'factura' => [
                'numero_factura' => $data['factura']['numero_factura'],
                'fecha_emision' => $data['factura']['fecha_emision'],
                'fecha_vencimiento' => $data['factura']['fecha_vencimiento'],
                'concepto_detalle' => $data['factura']['concepto_detalle'],
                'forma_pago' => $data['factura']['forma_pago'],
                'supplier_id' => $data['factura']['supplier_id'],
                'subtotal' => $data['factura']['subtotal'] ?? 0,
                'total_iva' => $data['factura']['total_iva'] ?? 0,
                'total' => $data['factura']['total'] ?? 0,
                'estado' => strtolower($data['factura']['estado'] ?? 'pendiente'),
                'user_id' => $data['factura']['user_id'] ?? 1
            ],
            'items' => []
        ];

        // Mapear items con snake_case
        foreach ($data['items'] as $item) {
            $itemData = [
                'detalle' => $item['detalle'],
                'unidad_medida' => $item['unidad_medida'],
                'cantidad' => (int)$item['cantidad'],
                'valor_unitario' => (float)$item['valor_unitario'],
                'porcentaje_iva' => (float)$item['porcentaje_iva']
            ];
            
            // CRÍTICO: Agregar product_id si existe
            if (isset($item['product_id']) && $item['product_id']) {
                $itemData['product_id'] = (int)$item['product_id'];
            }
            
            $javaData['items'][] = $itemData;
        }

        Log::info('Actualizando en Java:', $javaData);

        $response = Http::timeout(10)
                       ->put($this->javaApiUrl . "/facturas/{$id}", $javaData);

        if ($response->successful()) {
            $responseData = $response->json();
            
            if (isset($responseData['success']) && $responseData['success']) {
                return [
                    'success' => true,
                    'data' => $this->mapJavaFacturaToLaravel($responseData['data'])
                ];
            }
        }
        
        Log::warning('Java API update error: ' . $response->body());
        
    } catch (\Exception $e) {
        Log::warning('Java API error in updateFacturaInJava: ' . $e->getMessage());
    }

    return null;
}

    /**
     * Cambiar estado de factura en Java API
     */
    public function cambiarEstadoInJava($id, string $estado): ?array
    {
        if (!$this->useJavaAPI) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                           ->patch($this->javaApiUrl . "/facturas/{$id}/estado", [
                               'estado' => strtoupper($estado)
                           ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['success']) && $responseData['success']) {
                    return [
                        'success' => true,
                        'data' => $this->mapJavaFacturaToLaravel($responseData['data'])
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Java API error in cambiarEstadoInJava: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Eliminar factura en Java API
     */
 public function deleteFacturaInJava($id): bool
{
    if (!$this->useJavaAPI) {
        Log::warning('Java API está deshabilitada');
        return false;
    }

    try {
        Log::info("=== ELIMINANDO FACTURA {$id} EN JAVA ===");
        Log::info("URL: {$this->javaApiUrl}/facturas/{$id}");
        
        $response = Http::timeout(10)
                       ->delete($this->javaApiUrl . "/facturas/{$id}");

        Log::info("Status: " . $response->status());
        Log::info("Body: " . $response->body());

        if ($response->successful() || $response->status() === 204) {
            Log::info("✅ Factura eliminada en Java");
            return true;
        }
        
        Log::warning("Java rechazó la eliminación");
        return false;
        
    } catch (\Exception $e) {
        Log::error('Excepción en deleteFacturaInJava: ' . $e->getMessage());
        return false;
    }
}

    /**
     * Obtener estadísticas desde Java API
     */
    public function getStatsFromJava(): ?array
    {
        if (!$this->useJavaAPI) {
            return null;
        }

        try {
            $response = Http::timeout(5)->get($this->javaApiUrl . '/facturas/stats');
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['success']) && $data['success']) {
                    return $data['data'];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Java API error in getStatsFromJava: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Obtener proveedores desde Java API
     */
    public function getSuppliersFromJava(): ?array
    {
        if (!$this->useJavaAPI) {
            return null;
        }

        try {
            $response = Http::timeout(5)->get($this->javaApiUrl . '/suppliers');
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['success']) && $data['success']) {
                    return $data['data'];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Java API error in getSuppliersFromJava: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Sincronizar una factura específica desde Java a Laravel
     */
    public function syncFacturaFromJavaToLaravel($javaFactura): ?Factura
    {
        try {
            $laravelData = $this->mapJavaFacturaToLaravel($javaFactura);
            
            return Factura::updateOrCreate(
                ['numero_factura' => $laravelData['numero_factura']],
                $laravelData
            );
        } catch (\Exception $e) {
            Log::error('Error syncing factura from Java to Laravel: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sincronizar facturas masivamente desde Java a Laravel
     */
    public function syncAllFromJavaToLaravel(): array
    {
        $results = ['synced' => 0, 'errors' => 0, 'messages' => []];

        if (!$this->useJavaAPI) {
            $results['messages'][] = 'Java API está deshabilitada';
            return $results;
        }

        $page = 0;
        $size = 50;

        do {
            try {
                $javaResponse = $this->getFacturasFromJava([
                    'page' => $page,
                    'size' => $size
                ]);

                if (!$javaResponse || !$javaResponse['success']) {
                    break;
                }

                $facturas = $javaResponse['data'] ?? [];

                foreach ($facturas as $javaFactura) {
                    $syncResult = $this->syncFacturaFromJavaToLaravel($javaFactura);
                    
                    if ($syncResult) {
                        $results['synced']++;
                    } else {
                        $results['errors']++;
                    }
                }

                $page++;
                
            } catch (\Exception $e) {
                $results['errors']++;
                $results['messages'][] = "Error en página {$page}: " . $e->getMessage();
                break;
            }
            
        } while (count($facturas) === $size);

        return $results;
    }

    /**
     * Verificar conectividad con Java API
     */
    public function testJavaAPIConnection(): array
    {
        if (!$this->useJavaAPI) {
            return [
                'connected' => false,
                'message' => 'Java API está deshabilitada en configuración'
            ];
        }

        try {
            $response = Http::timeout(5)->get($this->javaApiUrl . '/facturas/stats');
            
            if ($response->successful()) {
                return [
                    'connected' => true,
                    'message' => 'Conexión exitosa con Java API',
                    'status_code' => $response->status()
                ];
            } else {
                return [
                    'connected' => false,
                    'message' => 'Java API respondió con error',
                    'status_code' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mapear factura de Java a formato Laravel
     */
 private function mapJavaFacturaToLaravel(array $javaFactura): array
{
    $mapped = [
        'numero_factura' => $javaFactura['numeroFactura'] ?? '',
        'fecha_emision' => $javaFactura['fechaEmision'] ?? null,
        'fecha_vencimiento' => $javaFactura['fechaVencimiento'] ?? null,
        'concepto_detalle' => $javaFactura['conceptoDetalle'] ?? '',
        'forma_pago' => $javaFactura['formaPago'] ?? '',
        'supplier_id' => $javaFactura['supplierId'] ?? $javaFactura['supplier']['id'] ?? null,
        'subtotal' => $javaFactura['subtotal'] ?? 0,
        'total_iva' => $javaFactura['totalIva'] ?? 0,
        'total' => $javaFactura['total'] ?? 0,
        'estado' => strtolower($javaFactura['estado'] ?? 'pendiente'),
        'user_id' => $javaFactura['userId'] ?? 1,
    ];

    // Agregar items si vienen
    if (isset($javaFactura['items'])) {
        $mapped['items'] = $javaFactura['items'];
    }

    return $mapped;
}

private function mapLaravelFacturaToJava(array $laravelData): array
{
    $mapped = [
        'factura' => [
            'numeroFactura' => $laravelData['numero_factura'],
            'fechaEmision' => $laravelData['fecha_emision'],
            'fechaVencimiento' => $laravelData['fecha_vencimiento'],
            'conceptoDetalle' => $laravelData['concepto_detalle'],
            'formaPago' => $laravelData['forma_pago'],
            'supplierId' => $laravelData['supplier_id'],
            'subtotal' => $laravelData['subtotal'] ?? 0,
            'totalIva' => $laravelData['total_iva'] ?? 0,
            'total' => $laravelData['total'] ?? 0,
            'estado' => strtoupper($laravelData['estado'] ?? 'PENDIENTE'),
            'userId' => $laravelData['user_id'] ?? 1
        ],
        'items' => []
    ];

    // Agregar items si existen
    if (isset($laravelData['items'])) {
        foreach ($laravelData['items'] as $item) {
            $mapped['items'][] = [
                'detalle' => $item['detalle'] ?? '',
                'unidadMedida' => $item['unidad_medida'] ?? '',
                'cantidad' => $item['cantidad'] ?? 1,
                'valorUnitario' => $item['valor_unitario'] ?? 0,
                'porcentajeIva' => $item['porcentaje_iva'] ?? 0,
                'product' => isset($item['product_id']) ? ['id' => $item['product_id']] : null
            ];
        }
    }

    return $mapped;
}
    /**
     * Verificar si Java API está habilitada y disponible
     */
    public function isJavaAPIAvailable(): bool
    {
        if (!$this->useJavaAPI) {
            return false;
        }

        $connection = $this->testJavaAPIConnection();
        return $connection['connected'];
    }

    
}