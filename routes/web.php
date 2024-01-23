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