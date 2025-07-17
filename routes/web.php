<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminEquiposController;
use App\Http\Controllers\AdminInformesController;
use App\Http\Controllers\AdminOrdersController;
use App\Http\Controllers\AdminFiles1Controller;
use App\Http\Controllers\AdminActasController;    
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
// ORDERS - Rutas para órdenes y equipos (SIN DUPLICADOS)
// ====================================================================

// Orders - Info y utilidades
Route::get('admin/orders/get-info/{id}', [AdminOrdersController::class, 'getInfo'])->where('id', '[0-9]+');

// Orders - PDF original (equipos)
Route::get('admin/orders/{id}/pdf', [AdminOrdersController::class, 'equiposPDF'])->where('id', '[0-9]+');

// Orders - Firma Digital
Route::get('admin/orders/{id}/view-pdf', [AdminOrdersController::class, 'viewPDF'])->where('id', '[0-9]+');
Route::post('admin/orders/{id}/save-signature', [AdminOrdersController::class, 'saveSignature'])->where('id', '[0-9]+');

// ====================================================================
// ACTAS - Rutas para actas de entrega (SIN DUPLICADOS)
// ====================================================================

// Acta - PDF original (descarga directa) - RUTA ORIGINAL
Route::get('/admin/actas/{id}', [AdminActasController::class, 'actasPDF'])->where('id', '[0-9]+');

// Actas - Firma Digital (NUEVAS RUTAS - SIN MIDDLEWARE CONFLICTIVO)
Route::get('admin/actas/{id}/view-pdf', [AdminActasController::class, 'viewActaPDF'])->where('id', '[0-9]+');
Route::get('admin/actas/{id}/pdf', [AdminActasController::class, 'getActaPDF'])->where('id', '[0-9]+');
Route::post('admin/actas/{id}/save-signature', [AdminActasController::class, 'saveActaSignature'])->where('id', '[0-9]+');
Route::get('admin/actas/{id}/signer-info', [AdminActasController::class, 'getActaSignerInfo'])->where('id', '[0-9]+');

// ====================================================================
// INFORMES - Rutas para informes
// ====================================================================

// Informes - PDF
Route::get('/admin/informes/{id}', [AdminInformesController::class, 'informeservicioPDF'])->where('id', '[0-9]+');