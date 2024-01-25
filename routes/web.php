<?php

use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

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


//Ruta para el index
Route::get('vehiculo', [VehiculoController::class, 'index'])->name('vehiculo.index');

Route::post('vehiculo/add', [VehiculoController::class, 'store'])->name('vehiculo.store');

//ver vehiculo
Route::get('vehiculo/show/{id}', [VehiculoController::class, 'show']);

//Eliminar un registro
Route::delete('vehiculo/destroy/{id}', [VehiculoController::class, 'destroy']);


//Acceso a la API
Route::get('vehicles/api', [VehiculoController::class, 'obtenerDataAPI']);
