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

Route::get('/admin/firestoreusers', [FirestoreUsersController::class, 'getData']);
Route::get('/userslist', 'FirestoreController@users');
Route::get('/admin/actas/{id}', [AdminActasController::class, 'actasPDF']);
//Route::get('/admin/equipos/{id}', [AdminEquiposController::class, 'equiposPDF']);
Route::get('/admin/informes/{id}', [AdminInformesController::class, 'informeservicioPDF']);
Route::get('admin/orders/{id}', [AdminOrdersController::class, 'getInfo']);
Route::get('admin/orders/{id}/pdf', [AdminOrdersController::class, 'equiposPDF']);
Route::get('admin/actas/{order_id}', [AdminActasController::class, 'actasPDF']);
Route::get('admin/orders/view-pdf/{id}', 'AdminOrdersController@viewPDF');
Route::post('admin/orders/save-signature/{id}', 'AdminOrdersController@saveSignature');

// Orders - Firma Digital (NUEVAS)
Route::get('admin/orders/{id}/view-pdf', [AdminOrdersController::class, 'viewPDF'])->where('id', '[0-9]+');
Route::post('admin/orders/save-signature/{id}', [AdminOrdersController::class, 'saveSignature'])->where('id', '[0-9]+');

// Orders - PDFs
Route::get('admin/orders/{id}/equipos-pdf', [AdminOrdersController::class, 'equiposPDF'])->where('id', '[0-9]+');
Route::get('admin/orders/{id}/pdf', [AdminOrdersController::class, 'equiposPDF'])->where('id', '[0-9]+');

// Orders - Info
Route::get('admin/orders/get-info/{id}', [AdminOrdersController::class, 'getInfo'])->where('id', '[0-9]+');

// Actas
Route::get('/admin/actas/{id}', [AdminActasController::class, 'actasPDF'])->where('id', '[0-9]+');

// Informes
Route::get('/admin/informes/{id}', [AdminInformesController::class, 'informeservicioPDF'])->where('id', '[0-9]+');