<?php

use App\Http\Controllers\CompaniaController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//API para probar registro del vehiculo
Route::post('/vehiculo', [VehiculoController::class, 'addApi']);

//API para agregar compañia
Route::post('/compania', [CompaniaController::class, 'addCompania']);

//Listado de todos los vehiculos
Route::get('/getVehiculos', [VehiculoController::class, 'listaVehiculos']);

//Busqueda de vehiculos
Route::get('/getVehiculos/{id}', [VehiculoController::class, 'buscarVehiculo']);

