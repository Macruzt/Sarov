<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\JavaSupplierService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    private JavaSupplierService $javaSupplierService;

    public function __construct(JavaSupplierService $javaSupplierService)
    {
        $this->javaSupplierService = $javaSupplierService;
    }

    /**
     * Mostrar página principal de proveedores
     */
    public function index()
    {
        return view('index');
    }

    /**
     * API: Obtener todos los proveedores (con fallback)
     */
    public function apiIndex(Request $request): JsonResponse
    {
        try {
            // Intentar obtener de Java API primero
            $params = [
                'page' => $request->get('page', 0),
                'size' => $request->get('size', 10),
                'sortBy' => $request->get('sortBy', 'nombreRazonSocial'),
                'sortDir' => $request->get('sortDir', 'asc'),
                'search' => $request->get('search')
            ];

            $result = $this->javaSupplierService->getAllSuppliers($params);

            if ($result && isset($result['success']) && $result['success']) {
                return response()->json($result);
            }

            // Fallback a Laravel si Java API falla
            $suppliers = Supplier::select('id', 'nombre_razon_social', 'nit', 'telefono', 'email')
                ->where('is_active', 1)
                ->orderBy('nombre_razon_social')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Proveedores obtenidos correctamente (desde Laravel)',
                'data' => $suppliers,
                'source' => 'laravel_db'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error loading suppliers: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar proveedores',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * API: Crear proveedor
     */
    public function apiStore(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombreRazonSocial' => 'required|string|max:255',
                'nit' => 'required|string|max:255',
                'direccion' => 'required|string|max:500',
                'telefono' => 'required|string|max:255',
                'email' => 'required|email|max:255'
            ]);

            $result = $this->javaSupplierService->createSupplier($validated);

            if ($result && isset($result['success']) && $result['success']) {
                return response()->json($result, 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al crear proveedor',
                'errors' => $result['error'] ?? 'Error desconocido'
            ], $result['status'] ?? 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Mostrar proveedor específico
     */
    public function show(int $id)
    {
        $supplier = $this->javaSupplierService->getSupplierById($id);

        if (!$supplier || !$supplier['success']) {
            abort(404, 'Proveedor no encontrado');
        }

        return view('suppliers.show', compact('supplier'));
    }

    /**
     * API: Obtener proveedor por ID
     */
    public function apiShow(int $id): JsonResponse
    {
        $result = $this->javaSupplierService->getSupplierById($id);

        if ($result && $result['success']) {
            return response()->json($result);
        }

        return response()->json([
            'success' => false,
            'message' => 'Proveedor no encontrado'
        ], 404);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(int $id)
    {
        $supplier = $this->javaSupplierService->getSupplierById($id);

        if (!$supplier || !$supplier['success']) {
            abort(404, 'Proveedor no encontrado');
        }

        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * API: Actualizar proveedor
     */
    public function apiUpdate(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombreRazonSocial' => 'required|string|max:255',
                'nit' => 'required|string|max:255',
                'direccion' => 'required|string|max:500',
                'telefono' => 'required|string|max:255',
                'email' => 'required|email|max:255'
            ]);

            $result = $this->javaSupplierService->updateSupplier($id, $validated);

            if ($result && isset($result['success']) && $result['success']) {
                return response()->json($result);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar proveedor',
                'errors' => $result['error'] ?? 'Error desconocido'
            ], $result['status'] ?? 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * API: Eliminar proveedor
     */
    public function apiDestroy(int $id): JsonResponse
    {
        $result = $this->javaSupplierService->deleteSupplier($id);

        if ($result && $result['success']) {
            return response()->json($result);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar proveedor'
        ], 400);
    }

    /**
     * API: Buscar proveedor por NIT
     */
    public function apiSearchByNit(string $nit): JsonResponse
    {
        $result = $this->javaSupplierService->getSupplierByNit($nit);

        if ($result && $result['success']) {
            return response()->json($result);
        }

        return response()->json([
            'success' => false,
            'message' => 'Proveedor no encontrado con NIT: ' . $nit
        ], 404);
    }

    /**
     * API: Lista simple para dropdowns
     */
    public function apiList(): JsonResponse
    {
        $result = $this->javaSupplierService->getActiveSuppliersList();

        if ($result) {
            return response()->json($result);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener lista de proveedores'
        ], 503);
    }

    /**
     * API: Estadísticas
     */
    public function apiStats(): JsonResponse
    {
        $result = $this->javaSupplierService->getSupplierStats();

        if ($result) {
            return response()->json($result);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener estadísticas'
        ], 503);
    }

    /**
     * Verificar estado de la API
     */
    public function apiHealthCheck(): JsonResponse
    {
        $isAvailable = $this->javaSupplierService->isApiAvailable();

        return response()->json([
            'java_api_available' => $isAvailable,
            'status' => $isAvailable ? 'OK' : 'DOWN'
        ]);
    }
}