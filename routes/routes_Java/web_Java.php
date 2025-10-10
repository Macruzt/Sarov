<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\IntegrationController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PÁGINA PRINCIPAL
// ==========================================
Route::get('/', function () {
    return view('index');
});

// ==========================================
// RUTAS WEB - PRODUCTS
// ==========================================
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::put('/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
});

// ==========================================
// RUTAS WEB - SUPPLIERS
// ==========================================
Route::prefix('suppliers')->name('suppliers.')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('index');
    Route::get('/create', [SupplierController::class, 'create'])->name('create');
    Route::get('/{id}', [SupplierController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('edit');
});

// ==========================================
// RUTAS WEB - FACTURAS
// ==========================================
Route::prefix('facturas')->name('facturas.')->group(function () {
    Route::get('/', [FacturaController::class, 'index'])->name('index');
    Route::get('/create', [FacturaController::class, 'create'])->name('create');
    Route::get('/{factura}', [FacturaController::class, 'show'])->name('show');
    Route::get('/{factura}/edit', [FacturaController::class, 'edit'])->name('edit');
});

// ==========================================
// RUTAS WEB - INTEGRATION
// ==========================================
Route::prefix('integration')->name('integration.')->group(function () {
    Route::get('/dashboard', [IntegrationController::class, 'dashboard'])->name('dashboard');
});

// ==========================================
// RUTAS API
// ==========================================
Route::prefix('api')->group(function () {
    
    // API - Products
    Route::prefix('products')->group(function () {
        Route::get('/test-connection', [ProductController::class, 'testConnection']);
        Route::delete('/{id}', [ProductController::class, 'apiDestroy']);
    });
    
    // API - Suppliers
    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'apiIndex']);
        Route::post('/', [SupplierController::class, 'apiStore']);
        Route::get('/list', [SupplierController::class, 'apiList']);
        Route::get('/stats', [SupplierController::class, 'apiStats']);
        Route::get('/health', [SupplierController::class, 'apiHealthCheck']);
        Route::get('/nit/{nit}', [SupplierController::class, 'apiSearchByNit']);
        Route::get('/{id}', [SupplierController::class, 'apiShow']);
        Route::put('/{id}', [SupplierController::class, 'apiUpdate']);
        Route::delete('/{id}', [SupplierController::class, 'apiDestroy']);
    });
    
    // API - Facturas
    Route::prefix('facturas')->group(function () {
        Route::get('/', [FacturaController::class, 'apiIndex']);
        Route::post('/', [FacturaController::class, 'apiStore']);
        Route::get('/stats', [FacturaController::class, 'apiStats']);
        Route::get('/sync', [FacturaController::class, 'apiSync']);
        Route::get('/test-connection', [FacturaController::class, 'apiTestConnection']);
        Route::get('/{id}', [FacturaController::class, 'apiShow']);
        Route::put('/{id}', [FacturaController::class, 'apiUpdate']);
        Route::patch('/{id}/estado', [FacturaController::class, 'apiCambiarEstado']);
        Route::delete('/{id}', [FacturaController::class, 'apiDestroy']);
    });
    
    // API - Integration
    Route::prefix('integration')->group(function () {
        Route::get('/status', [IntegrationController::class, 'status']);
        Route::get('/test-connection', [IntegrationController::class, 'testConnection']);
        Route::post('/sync-from-java', [IntegrationController::class, 'syncFromJava']);
        Route::post('/configure', [IntegrationController::class, 'configure']);
        Route::get('/logs', [IntegrationController::class, 'logs']);
        Route::post('/clear-cache', [IntegrationController::class, 'clearCache']);
    });

    Route::get('/products', [ProductController::class, 'apiIndex']);
});