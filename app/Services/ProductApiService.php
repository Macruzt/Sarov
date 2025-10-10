<?php
// Ubicación: app/Services/ProductApiService.php
// Qué hace: Servicio que tu ProductController ya está usando

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ProductApiService
{
    private $client;
    private $baseUrl;
    
    public function __construct()
    {
        $this->baseUrl = config('services.java_api.url', 'http://localhost:8081/api');
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
        ]);
    }
    
    /**
     * Realizar petición HTTP
     */
    private function makeRequest($method, $endpoint, $data = null, $params = [])
    {
        try {
            $options = [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ];
            
            if ($data) {
                $options['json'] = $data;
            }
            
            if (!empty($params)) {
                $options['query'] = $params;
            }
            
            $response = $this->client->request($method, $this->baseUrl . $endpoint, $options);
            return json_decode($response->getBody(), true);
            
        } catch (RequestException $e) {
            Log::error('ProductApiService Error: ' . $e->getMessage());
            
            if ($e->hasResponse()) {
                $errorBody = json_decode($e->getResponse()->getBody(), true);
                return ['error' => $errorBody['message'] ?? 'Error en el servidor Java'];
            }
            
            return null; // Retorna null cuando no hay conexión
        }
    }
    
    /**
     * Listar productos (método que usa tu controller)
     */
   public function list($params = [])
{
    $response = $this->makeRequest('GET', '/products', null, $params);
    
    if ($response === null) {
        return null;
    }
    
    // Mapear la respuesta de Java al formato que espera Laravel
    return [
        'data' => $response['content'] ?? [],
        'totalElements' => $response['totalElements'] ?? 0,
        'number' => $response['number'] ?? 0,
        'size' => $response['size'] ?? 100,
        'totalPages' => $response['totalPages'] ?? 0
    ];
}
    
    /**
     * Encontrar producto por ID (método que usa tu controller)
     */
    public function find($id)
    {
        return $this->makeRequest('GET', "/products/{$id}");
    }
    
    /**
     * Crear producto (método que usa tu controller)
     */
    public function store($data)
    {
        return $this->makeRequest('POST', '/products', $data);
    }
    
    /**
     * Actualizar producto
     */
    public function update($id, $data)
    {
        return $this->makeRequest('PUT', "/products/{$id}", $data);
    }
    
    /**
     * Eliminar producto
     */
    public function delete($id)
    {
        return $this->makeRequest('DELETE', "/products/{$id}");
    }
    
    /**
     * Obtener unidades de medida
     */
    public function getUnidadesMedida()
    {
        return $this->makeRequest('GET', '/products/unidades-medida');
    }
    
    /**
     * Obtener porcentajes de IVA
     */
    public function getPorcentajesIva()
    {
        return $this->makeRequest('GET', '/products/porcentajes-iva');
    }
    
    /**
     * Método para probar conexión
     */
    public function testConnection()
    {
        try {
            $response = $this->makeRequest('GET', '/products', null, ['size' => 1]);
            return $response !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
 * Actualizar stock de un producto
 */
public function updateStock($productId, $cantidad, $operacion = 'sumar')
{
    try {
        $response = $this->makeRequest('PATCH', "/products/{$productId}/stock", [
            'cantidad' => (int)$cantidad,
            'operacion' => $operacion
        ]);

        if ($response && !isset($response['error'])) {
            Log::info("Stock actualizado para producto {$productId}: {$operacion} {$cantidad}");
            return true;
        }

        Log::error("Error actualizando stock: " . ($response['error'] ?? 'Unknown error'));
        return false;
        
    } catch (\Exception $e) {
        Log::error("Error en updateStock: " . $e->getMessage());
        return false;
    }
}
}