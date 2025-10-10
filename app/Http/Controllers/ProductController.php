<?php

namespace App\Http\Controllers;

use App\Services\ProductApiService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $api;

    public function __construct(ProductApiService $api)
    {
        $this->api = $api;
    }

    public function index(Request $request)
    {
        // Pasa parámetros de paginación al microservicio
        $params = [
            'page' => $request->get('page', 0),
            'size' => $request->get('size', 10),
            'search' => $request->get('search', null)
        ];

        $data = $this->api->list($params);

        if (!$data) {
            return view('products.index', [
                'products' => [],
                'meta' => [],
                'error' => 'No se pudo conectar con el microservicio'
            ]);
        }

        return view('products.index', [
            'products' => $data['data'] ?? [],
            'meta' => $data
        ]);
    }

    public function show($id)
    {
        $data = $this->api->find($id);
        if (!$data) {
            return redirect()->route('products.index')->with('error', 'Producto no encontrado');
        }
        return view('products.show', ['product' => $data['data'] ?? $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'detalle' => 'required|string|max:1000',
            'unidadMedida' => 'required|string|max:255',
            'valorUnitario' => 'required|numeric|min:0.01',
            'porcentajeIva' => 'required|numeric|min:0|max:100'
        ]);

        $payload = $request->only([
            'detalle', 'unidadMedida', 'valorUnitario', 'porcentajeIva',
            'sku', 'categoria', 'stockActual'
        ]);
        
        $response = $this->api->store($payload);

        if (is_null($response)) {
            return back()->with('error', 'Error al crear producto')->withInput();
        }

        if (isset($response['success']) && $response['success']) {
            return redirect()->route('products.index')->with('success', 'Producto creado exitosamente');
        }

        return back()->with('error', $response['message'] ?? 'Error desconocido')->withInput();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'detalle' => 'required|string|max:1000',
            'unidadMedida' => 'required|string|max:255',
            'valorUnitario' => 'required|numeric|min:0.01',
            'porcentajeIva' => 'required|numeric|min:0|max:100'
        ]);

        $payload = $request->only([
            'detalle', 'unidadMedida', 'valorUnitario', 'porcentajeIva',
            'sku', 'categoria', 'stockActual'
        ]);
        
        $response = $this->api->update($id, $payload);

        if (is_null($response)) {
            return back()->with('error', 'Error al actualizar producto');
        }
        
        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy($id)
    {
        $response = $this->api->delete($id);

        if (is_null($response)) {
            return back()->with('error', 'Error al eliminar producto');
        }
        
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente');
    }

    public function apiDestroy($id)
    {
        $response = $this->api->delete($id);

        if (is_null($response)) {
            return response()->json(['success' => false, 'message' => 'Error de conexión'], 500);
        }
        
        return response()->json(['success' => true, 'message' => 'Producto eliminado exitosamente']);
    }

    public function testConnection()
    {
        $isConnected = $this->api->testConnection();
        
        return response()->json([
            'success' => $isConnected,
            'message' => $isConnected ? 'Conexión exitosa con Java' : 'No se pudo conectar con Java'
        ]);
    }

    // esto se ah=gregar opara que muestre la peticion de los productos en formato json
    public function apiIndex(Request $request)
{
    $params = [
        'page' => $request->get('page', 0),
        'size' => $request->get('size', 100),
        'search' => $request->get('search', null)
    ];

    $data = $this->api->list($params);

    if (!$data) {
        return response()->json([
            'success' => false,
            'message' => 'No se pudo conectar con el microservicio',
            'data' => []
        ], 500);
    }

    return response()->json([
        'success' => true,
        'data' => $data['data'] ?? [],
        'pagination' => [
            'total' => $data['totalElements'] ?? 0,
            'current_page' => ($data['number'] ?? 0) + 1,
            'per_page' => $data['size'] ?? 100
        ]
    ]);
}
}