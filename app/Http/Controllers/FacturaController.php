<?php
// app/Http/Controllers/FacturaController.php - USANDO SERVICE

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Supplier;
use App\Services\FacturaSyncService;
use App\Services\ProductApiService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class FacturaController extends Controller
{
    protected FacturaSyncService $syncService;
    protected ProductApiService $productService;

    public function __construct(FacturaSyncService $syncService, ProductApiService $productService)
    {
       $this->syncService = $syncService;
    $this->productService = $productService;
}
    

    /**
     * Mostrar la vista principal de facturas
     */
    public function index()
    {
        return view('facturas.index');
    }

    /**
     * API: Obtener todas las facturas (con fallback automático)
     */
    public function apiIndex(Request $request): JsonResponse
    {
        try {
            // Intentar obtener desde Java API
            $params = [
                'page' => max(0, ($request->get('page', 1) - 1)), // Convertir a 0-based para Java
                'size' => $request->get('per_page', 15),
                'search' => $request->get('search')
            ];

            // Mapear filtros de estado
            $estado = $request->get('estado');
            if ($estado && $estado !== 'vencidas') {
                $params['estado'] = $estado;
            }

            $javaResponse = $this->syncService->getFacturasFromJava($params);
            
            if ($javaResponse && $javaResponse['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $javaResponse['data'],
                    'pagination' => $javaResponse['pagination'],
                    'source' => 'java_api'
                ]);
            }

            // Fallback a Laravel
            return $this->getFacturasFromLaravel($request);

        } catch (\Exception $e) {
            Log::error('Error in apiIndex: ' . $e->getMessage());
            return $this->getFacturasFromLaravel($request);
        }
    }

    /**
     * Obtener facturas desde Laravel (fallback)
     */
    private function getFacturasFromLaravel(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $estado = $request->get('estado');

            $query = Factura::with('supplier')->orderBy('created_at', 'desc');

            if ($search) {
                $query->search($search);
            }

            if ($estado) {
                switch ($estado) {
                    case 'vencidas':
                        $query->vencidas();
                        break;
                    default:
                        $query->where('estado', $estado);
                        break;
                }
            }

            $facturas = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $facturas->items(),
                'pagination' => [
                    'current_page' => $facturas->currentPage(),
                    'last_page' => $facturas->lastPage(),
                    'per_page' => $facturas->perPage(),
                    'total' => $facturas->total(),
                    'from' => $facturas->firstItem(),
                    'to' => $facturas->lastItem()
                ],
                'source' => 'laravel_db'
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading facturas from Laravel: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar facturas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Crear nueva factura
     */
    /**
 * API: Crear nueva factura CON ITEMS
 */
public function apiStore(Request $request): JsonResponse
{
    try {
        // Validar estructura básica
        $request->validate([
            'factura' => 'required|array',
            'items' => 'required|array|min:1',
        ]);

        $facturaData = $request->input('factura');
        $itemsData = $request->input('items');

        // Validar datos de factura
        $validatedFactura = validator($facturaData, [
            'numero_factura' => 'required|string|max:255|unique:facturas,numero_factura',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_emision',
            'concepto_detalle' => 'required|string',
            'forma_pago' => 'required|string|max:255',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'subtotal' => 'nullable|numeric|min:0',
            'total_iva' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'estado' => ['nullable', Rule::in(['pendiente', 'pagada', 'vencida'])],
            'user_id' => 'nullable|integer'
        ])->validate();

        // Validar items
        foreach ($itemsData as $index => $item) {
            validator($item, [
                'detalle' => 'required|string|max:1000',
                'unidad_medida' => 'required|string|max:255',
                'cantidad' => 'required|integer|min:1',
                'valor_unitario' => 'required|numeric|min:0',
                'porcentaje_iva' => 'required|numeric|min:0|max:100',
            ], [
                'detalle.required' => "El detalle del item " . ($index + 1) . " es obligatorio",
                'cantidad.required' => "La cantidad del item " . ($index + 1) . " es obligatoria",
            ])->validate();
        }

        // Intentar crear en Java API primero
        $javaResponse = $this->syncService->createFacturaInJava([
            'factura' => $validatedFactura,
            'items' => $itemsData
        ]);
        
        if ($javaResponse && $javaResponse['success']) {
            // Sincronizar a Laravel también
            try {
                $this->createFacturaWithItemsInLaravel($validatedFactura, $itemsData);
            } catch (\Exception $e) {
                Log::warning('Failed to sync factura to Laravel: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Factura creada exitosamente',
                'data' => $javaResponse['data'],
                'source' => 'java_api'
            ], 201);
        }

        // Fallback a Laravel
        $factura = $this->createFacturaWithItemsInLaravel($validatedFactura, $itemsData);
        
        return response()->json([
            'success' => true,
            'message' => 'Factura creada exitosamente',
            'data' => $factura->load(['supplier', 'items']),
            'source' => 'laravel_db'
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Datos inválidos',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error creating factura: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al crear factura',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * API: Mostrar factura específica
     */
    public function apiShow($id): JsonResponse
    {
        try {
            // Intentar obtener de Java primero
            $javaResponse = $this->syncService->getFacturaFromJava($id);
            
            if ($javaResponse && $javaResponse['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $javaResponse['data'],
                    'source' => 'java_api'
                ]);
            }

            // Fallback a Laravel
            $factura = Factura::with(['supplier', 'items.product'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $factura,
            'source' => 'laravel_db'
        ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada'
            ], 404);
        }
    }

    /**
     * API: Actualizar factura
     */
public function apiUpdate(Request $request, $id): JsonResponse
{
    try {
        // Validar estructura básica
        $request->validate([
            'factura' => 'required|array',
            'items' => 'nullable|array',
        ]);

        $facturaData = $request->input('factura');
        $itemsData = $request->input('items', []);

        // Validar datos de factura
        validator($facturaData, [
            'numero_factura' => 'required|string|max:255|unique:facturas,numero_factura,' . $id,
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_emision',
            'concepto_detalle' => 'required|string',
            'forma_pago' => 'required|string|max:255',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'subtotal' => 'nullable|numeric|min:0',
            'total_iva' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'estado' => ['nullable', Rule::in(['pendiente', 'pagada', 'vencida'])],
            'user_id' => 'nullable|integer'
        ])->validate();

        // Validar items si vienen
        if (!empty($itemsData)) {
            foreach ($itemsData as $index => $item) {
                validator($item, [
                    'detalle' => 'required|string|max:1000',
                    'unidad_medida' => 'required|string|max:255',
                    'cantidad' => 'required|integer|min:1',
                    'valor_unitario' => 'required|numeric|min:0',
                    'porcentaje_iva' => 'required|numeric|min:0|max:100',
                ])->validate();
            }
        }

        // Intentar actualizar en Java primero - USA $facturaData no $validatedFactura
        $javaResponse = $this->syncService->updateFacturaInJava($id, [
            'factura' => $facturaData,  // ← CAMBIO CRÍTICO AQUÍ
            'items' => $itemsData
        ]);
            
        if ($javaResponse && $javaResponse['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Factura actualizada exitosamente',
                'data' => $javaResponse['data'],
                'source' => 'java_api'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Java API no respondió. Error: ' . json_encode($javaResponse)
        ], 500);

    } catch (\Exception $e) {
        Log::error('Error updating factura: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar factura',
            'error' => $e->getMessage()
        ], 500);
    }
}

/**
 * Actualizar factura con items en Laravel
 */
private function updateFacturaWithItemsInLaravel($id, array $facturaData, array $itemsData): Factura
{
    $factura = Factura::findOrFail($id);
    
    // Actualizar datos de la factura
    $factura->update($facturaData);

    // Obtener items existentes
    $itemsExistentes = $factura->items()->get()->keyBy('id');
    $itemIdsMantenidos = [];

    foreach ($itemsData as $itemData) {
        $itemExistenteId = null;
        
        // Buscar si es un item existente comparando por product_id
        foreach ($itemsExistentes as $existingItem) {
            if (isset($itemData['product_id']) && $existingItem->product_id == $itemData['product_id']) {
                $itemExistenteId = $existingItem->id;
                break;
            }
        }
        
        if ($itemExistenteId && isset($itemsExistentes[$itemExistenteId])) {
            // ACTUALIZAR item existente
            $oldItem = $itemsExistentes[$itemExistenteId];
            
            // Revertir stock anterior solo si cambió la cantidad
            if ($oldItem->cantidad != $itemData['cantidad'] && $oldItem->product_id) {
                //$this->productService->updateStock($oldItem->product_id, $oldItem->cantidad, 'restar');
            }
            
            $oldItem->update([
                'detalle' => $itemData['detalle'],
                'unidad_medida' => $itemData['unidad_medida'],
                'cantidad' => $itemData['cantidad'],
                'valor_unitario' => $itemData['valor_unitario'],
                'porcentaje_iva' => $itemData['porcentaje_iva'],
            ]);
            
            // Aplicar nuevo stock
            if ($oldItem->cantidad != $itemData['cantidad'] && $oldItem->product_id) {
                //$this->productService->updateStock($oldItem->product_id, $itemData['cantidad'], 'sumar');
            }
            
            $itemIdsMantenidos[] = $itemExistenteId;
        } else {
            // CREAR nuevo item
            $newItem = $factura->items()->create([
                'detalle' => $itemData['detalle'],
                'unidad_medida' => $itemData['unidad_medida'],
                'cantidad' => $itemData['cantidad'],
                'valor_unitario' => $itemData['valor_unitario'],
                'porcentaje_iva' => $itemData['porcentaje_iva'],
                'product_id' => $itemData['product_id'] ?? null
            ]);
            
            // Actualizar stock del nuevo item
            if (isset($itemData['product_id']) && $itemData['product_id']) {
                //$this->productService->updateStock($itemData['product_id'], $itemData['cantidad'], 'sumar');
            }
            
            $itemIdsMantenidos[] = $newItem->id;
        }
    }

    // Eliminar items que ya no están
    $itemsAEliminar = $factura->items()->whereNotIn('id', $itemIdsMantenidos)->get();
    foreach ($itemsAEliminar as $item) {
        if ($item->product_id) {
            //$this->productService->updateStock($item->product_id, $item->cantidad, 'restar');
        }
        $item->delete();
    }

    return $factura->fresh(['items']);
}
/**
     * API: Cambiar estado de factura
     */
    public function apiCambiarEstado(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'estado' => ['required', Rule::in(['pendiente', 'pagada', 'vencida'])]
            ]);

            // Intentar cambiar en Java primero
            $javaResponse = $this->syncService->cambiarEstadoInJava($id, $validated['estado']);
            
            if ($javaResponse && $javaResponse['success']) {
                // Sincronizar a Laravel también
                try {
                    $factura = Factura::find($id);
                    if ($factura) {
                        $factura->update(['estado' => $validated['estado']]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to sync status change to Laravel: ' . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Estado actualizado exitosamente',
                    'data' => $javaResponse['data'],
                    'source' => 'java_api'
                ]);
            }

            // Fallback a Laravel
            $factura = Factura::findOrFail($id);
            $factura->update(['estado' => $validated['estado']]);

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente',
                'data' => $factura,
                'source' => 'laravel_db'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar estado'
            ], 500);
        }
    }

    /**
     * API: Eliminar factura
     */
 public function apiDestroy($id): JsonResponse
{
    try {
        // Obtener items ANTES de eliminar
        $factura = Factura::with('items')->find($id);
        
        if (!$factura) {
            return response()->json([
                'success' => false,
                'message' => 'Factura no encontrada'
            ], 404);
        }
        
        // Guardar items para revertir stock después
        $items = $factura->items->toArray();
        
        // Eliminar en Java
        $javaDeleted = $this->syncService->deleteFacturaInJava($id);
        
        if ($javaDeleted) {
            // Revertir stock en Laravel
            foreach ($items as $item) {
                if (isset($item['product_id']) && $item['product_id']) {
                    $this->productService->updateStock(
                        $item['product_id'], 
                        $item['cantidad'], 
                        'restar'
                    );
                    Log::info("Stock revertido: Producto {$item['product_id']} - {$item['cantidad']}");
                }
            }
            
            // Eliminar en Laravel
            $factura->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Factura eliminada exitosamente',
                'source' => 'java_api'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Java API no pudo eliminar la factura'
        ], 500);

    } catch (\Exception $e) {
        Log::error('Error deleting factura: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar factura',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * API: Obtener estadísticas
     */
    public function apiStats(): JsonResponse
    {
        try {
            // Intentar obtener de Java primero
            $javaStats = $this->syncService->getStatsFromJava();
            
            if ($javaStats) {
                return response()->json([
                    'success' => true,
                    'data' => $javaStats,
                    'source' => 'java_api'
                ]);
            }

            // Fallback a Laravel
            $stats = [
                'total_facturas' => Factura::count(),
                'pendientes' => Factura::pendientes()->count(),
                'pagadas' => Factura::pagadas()->count(),
                'vencidas' => Factura::vencidas()->count(),
                'total_pendiente' => Factura::pendientes()->sum('total'),
                'total_pagado' => Factura::pagadas()->sum('total'),
                'proximas_vencer' => Factura::pendientes()
                    ->whereBetween('fecha_vencimiento', [now(), now()->addDays(7)])
                    ->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'source' => 'laravel_db'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    /**
     * API: Sincronizar facturas desde Java
     */
    public function apiSync(): JsonResponse
    {
        try {
            $results = $this->syncService->syncAllFromJavaToLaravel();
            
            return response()->json([
                'success' => true,
                'message' => 'Sincronización completada',
                'data' => $results
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in sync: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error durante la sincronización',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Verificar conexión con Java API
     */
    public function apiTestConnection(): JsonResponse
    {
        $connectionTest = $this->syncService->testJavaAPIConnection();
        
        return response()->json([
            'success' => $connectionTest['connected'],
            'message' => $connectionTest['message'],
            'data' => $connectionTest
        ]);
    }

    // Métodos para vistas (sin cambios)
    public function create()
    {
        $suppliers = $this->getActiveSuppliers();
        $estados = Factura::getEstados();
        return view('facturas.create', compact('suppliers', 'estados'));
    }

    public function show($id)
    {
        $factura = $this->getFacturaFromBothSources($id);
        return view('facturas.show', compact('factura'));
    }

    public function edit($id)
    {
        $factura = $this->getFacturaFromBothSources($id);
        $suppliers = $this->getActiveSuppliers();
        $estados = Factura::getEstados();
        return view('facturas.edit', compact('factura', 'suppliers', 'estados'));
    }


    private function getFacturaFromBothSources($id)
    {
        // Intentar de Java primero
        $javaResponse = $this->syncService->getFacturaFromJava($id);
        
        if ($javaResponse && $javaResponse['success']) {
            return (object) $javaResponse['data'];
        }

        // Fallback a Laravel
        return Factura::with('supplier')->findOrFail($id);
    }

    private function validateFacturaData(Request $request, $id = null): array
    {
        $rules = [
            'numero_factura' => 'required|string|max:255',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'required|date|after_or_equal:fecha_emision',
            'concepto_detalle' => 'required|string',
            'forma_pago' => 'required|string|max:255',
            'supplier_id' => 'required|integer',
            'subtotal' => 'nullable|numeric|min:0',
            'total_iva' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'estado' => ['nullable', Rule::in(['pendiente', 'pagada', 'vencida'])],
            'user_id' => 'nullable|integer'
        ];

        // Si es actualización, permitir el mismo número de factura
        if ($id) {
            $rules['numero_factura'] .= '|unique:facturas,numero_factura,' . $id;
        } else {
            $rules['numero_factura'] .= '|unique:facturas,numero_factura';
        }

        return $request->validate($rules);
    }

    private function getActiveSuppliers()
    {
        // Intentar obtener de Java API primero
        $suppliers = $this->syncService->getSuppliersFromJava();
        
        if ($suppliers) {
            return collect($suppliers);
        }

        // Fallback a Laravel
        try {
            return Supplier::where('is_active', true)->get();
        } catch (\Exception $e) {
            Log::warning('Could not fetch suppliers from Laravel: ' . $e->getMessage());
            return collect();
        }
    }
    
    /**
 * Crear factura con items en Laravel
 */
private function createFacturaWithItemsInLaravel(array $facturaData, array $itemsData): Factura
{
    $facturaData['user_id'] = $facturaData['user_id'] ?? 1;
    $facturaData['estado'] = $facturaData['estado'] ?? Factura::ESTADO_PENDIENTE;

    // Crear factura
    $factura = Factura::create($facturaData);

    // Crear items Y actualizar stock
    foreach ($itemsData as $itemData) {
        $factura->items()->create([
            'detalle' => $itemData['detalle'],
            'unidad_medida' => $itemData['unidad_medida'],
            'cantidad' => $itemData['cantidad'],
            'valor_unitario' => $itemData['valor_unitario'],
            'porcentaje_iva' => $itemData['porcentaje_iva'],
            'product_id' => $itemData['product_id'] ?? null
        ]);

        // Actualizar stock si tiene product_id
        if (isset($itemData['product_id']) && $itemData['product_id']) {
            $this->productService->updateStock(
                $itemData['product_id'], 
                $itemData['cantidad'], 
                'sumar'
            );
            
            Log::info("Stock actualizado: Producto {$itemData['product_id']} + {$itemData['cantidad']}");
        }
    }

    return $factura;
}
}