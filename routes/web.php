<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminEquiposController;
use App\Http\Controllers\AdminInformesController;
use App\Http\Controllers\AdminOrdersController;
use App\Http\Controllers\AdminFiles1Controller;
use App\Http\Controllers\AdminActasController;
use App\Http\Controllers\AdminDocumentsController;
use App\Http\Controllers\FirestoreUsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// ====================================================================
// FIRESTORE - Rutas para usuarios de Firestore
// ====================================================================

Route::get('/admin/firestoreusers', [FirestoreUsersController::class, 'getData']);
Route::get('/userslist', 'FirestoreController@users');

// ====================================================================
// ORDERS - Rutas para órdenes y equipos
// ====================================================================

// Orders - Info y utilidades
Route::get('admin/orders/get-info/{id}', [AdminOrdersController::class, 'getInfo'])
    ->where('id', '[0-9]+')
    ->name('orders.get-info');

// Orders - PDF para descarga directa (EQUIPOS) - Ruta original
Route::get('admin/orders/{id}/equipos-pdf', [AdminOrdersController::class, 'equiposPDF'])
    ->where('id', '[0-9]+')
    ->name('orders.equipos-pdf');

// Orders - Vista previa del PDF para el modal de firma
Route::get('admin/orders/{id}/view-pdf', [AdminOrdersController::class, 'viewPDF'])
    ->where('id', '[0-9]+')
    ->name('orders.view-pdf');

// Orders - PDF sin firmar para el sistema de firma digital
Route::get('admin/orders/{id}/pdf', [AdminOrdersController::class, 'getOrderPDF'])
    ->where('id', '[0-9]+')
    ->name('orders.get-pdf');

// Orders - Guardar firma individual (método anterior)
Route::post('admin/orders/{id}/save-signature', [AdminOrdersController::class, 'saveSignature'])
    ->where('id', '[0-9]+')
    ->name('orders.save-signature');

// Orders - Guardar PDF completo firmado en base de datos (NUEVO)
Route::post('admin/orders/{id}/save-signed-pdf', [AdminOrdersController::class, 'saveSignedOrderPDF'])
    ->where('id', '[0-9]+')
    ->name('orders.save-signed-pdf');

// Orders - Obtener información del firmante
Route::get('admin/orders/{id}/signer-info', [AdminOrdersController::class, 'getSignerInfo'])
    ->where('id', '[0-9]+')
    ->name('orders.signer-info');

// ====================================================================
// ACTAS - Rutas para actas de entrega
// ====================================================================

// Actas - PDF para descarga directa - RUTA ORIGINAL
Route::get('/admin/actas/{id}/download', [AdminActasController::class, 'actasPDF'])
    ->where('id', '[0-9]+')
    ->name('actas.download-pdf');

// Actas - Vista previa del PDF para el modal de firma
Route::get('admin/actas/{id}/view-pdf', [AdminActasController::class, 'viewActaPDF'])
    ->where('id', '[0-9]+')
    ->name('actas.view-pdf');

// Actas - PDF sin firmar para el sistema de firma digital
Route::get('admin/actas/{id}/pdf', [AdminActasController::class, 'getActaPDF'])
    ->where('id', '[0-9]+')
    ->name('actas.get-pdf');

// Actas - Guardar firma individual (método anterior)
Route::post('admin/actas/{id}/save-signature', [AdminActasController::class, 'saveActaSignature'])
    ->where('id', '[0-9]+')
    ->name('actas.save-signature');

// Actas - Guardar PDF completo firmado en base de datos (NUEVO)
Route::post('admin/actas/{id}/save-signed-pdf', [AdminActasController::class, 'saveSignedActaPDF'])
    ->where('id', '[0-9]+')
    ->name('actas.save-signed-pdf');

// Actas - Obtener información del firmante
Route::get('admin/actas/{id}/signer-info', [AdminActasController::class, 'getActaSignerInfo'])
    ->where('id', '[0-9]+')
    ->name('actas.signer-info');

// ====================================================================
// DOCUMENTS - Rutas para gestión de documentos automáticos
// ====================================================================

// Descargar documento de recepción firmado automáticamente
Route::get('admin/documents/download-reception/{orderId}', [AdminDocumentsController::class, 'downloadReception'])
    ->where('orderId', '[0-9]+')
    ->name('documents.download-reception');

// Descargar acta de entrega firmada automáticamente
Route::get('admin/documents/download-delivery/{orderId}', [AdminDocumentsController::class, 'downloadDelivery'])
    ->where('orderId', '[0-9]+')
    ->name('documents.download-delivery');

// ====================================================================
// INFORMES - Rutas para informes
// ====================================================================

// Informes - PDF
Route::get('/admin/informes/{id}', [AdminInformesController::class, 'informeservicioPDF'])
    ->where('id', '[0-9]+')
    ->name('informes.pdf');

    // ====================================================================
// DOCUMENTS - Rutas para gestión de documentos automáticos
// ====================================================================

// Descargar documento de recepción firmado automáticamente
Route::get('admin/documents/download-reception/{orderId}', [AdminDocumentsController::class, 'downloadReception'])
    ->where('orderId', '[0-9]+')
    ->name('documents.download-reception');

// Descargar acta de entrega firmada automáticamente
Route::get('admin/documents/download-delivery/{orderId}', [AdminDocumentsController::class, 'downloadDelivery'])
    ->where('orderId', '[0-9]+')
    ->name('documents.download-delivery');

// ====================================================================
// DOCUMENTS - Rutas adicionales para ver y descargar documentos firmados
// ====================================================================

// Ver documento firmado desde service_order_documents
Route::get('admin/documents/view-document/{id}', [AdminDocumentsController::class, 'viewDocument'])
    ->where('id', '[0-9]+')
    ->name('documents.view-document');

// Descargar documento firmado desde service_order_documents
Route::get('admin/documents/download-document/{id}', [AdminDocumentsController::class, 'downloadDocument'])
    ->where('id', '[0-9]+')
    ->name('documents.download-document');

// ====================================================================
// INFORMES - Rutas para informes
// ====================================================================
Route::get('admin/documents/get-order-documents/{order_id}', 'AdminDocumentsController@getOrderDocuments');
Route::get('admin/orders/download-document/{order_id}/{type}', 'AdminOrdersController@downloadDocumentPDF');
Route::post('admin/actas/{id}/save-signed-pdf', 'AdminActasController@saveSignedActaPDF');
Route::get('admin/actas/{id}/get-pdf', 'AdminActasController@getActaPDF');
