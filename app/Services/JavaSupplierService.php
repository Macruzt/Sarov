<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class JavaSupplierService
{
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.java_api.url');
        $this->timeout = config('services.java_api.timeout', 30);
    }

    /**
     * Obtener todos los proveedores con paginación
     */
    public function getAllSuppliers(array $params = [])
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . '/suppliers', $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Error getting suppliers from Java API', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exception getting suppliers from Java API: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar proveedor por NIT
     */
    public function getSupplierByNit(string $nit)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . "/suppliers/nit/{$nit}");

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error("Error getting supplier by NIT {$nit}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener proveedor por ID
     */
    public function getSupplierById(int $id)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . "/suppliers/{$id}");

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error("Error getting supplier by ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Crear nuevo proveedor
     */
    public function createSupplier(array $data)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->post($this->baseUrl . '/suppliers', $data);

            if ($response->successful()) {
                // Limpiar cache si existe
                Cache::forget('active_suppliers_list');
                return $response->json();
            }

            return [
                'success' => false,
                'error' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Error creating supplier in Java API: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Actualizar proveedor
     */
    public function updateSupplier(int $id, array $data)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->put($this->baseUrl . "/suppliers/{$id}", $data);

            if ($response->successful()) {
                Cache::forget('active_suppliers_list');
                return $response->json();
            }

            return [
                'success' => false,
                'error' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error("Error updating supplier {$id}: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Eliminar proveedor
     */
    public function deleteSupplier(int $id)
    {
        try {
            $response = Http::timeout($this->timeout)
                ->delete($this->baseUrl . "/suppliers/{$id}");

            if ($response->successful()) {
                Cache::forget('active_suppliers_list');
                return $response->json();
            }

            return [
                'success' => false,
                'error' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error("Error deleting supplier {$id}: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtener lista de proveedores activos (para dropdowns)
     */
    public function getActiveSuppliersList()
    {
        return Cache::remember('active_suppliers_list', 300, function () {
            try {
                $response = Http::timeout($this->timeout)
                    ->get($this->baseUrl . '/suppliers/list');

                return $response->successful() ? $response->json() : null;
            } catch (\Exception $e) {
                Log::error('Error getting active suppliers list: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Obtener estadísticas de proveedores
     */
    public function getSupplierStats()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->baseUrl . '/suppliers/stats');

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Error getting supplier stats: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verificar si la API Java está disponible
     */
    public function isApiAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get($this->baseUrl . '/suppliers/stats');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}