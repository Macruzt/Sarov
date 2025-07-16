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