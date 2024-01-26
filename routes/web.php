<?php

use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;
use \App\Mail\ContactanosMailable;
use Illuminate\Support\Facades\Mail;
use \App\Mail\SenVehiculoMail;

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

//Route para correos MAIL
Route::get('mail/contact', function (){
    Mail::to('jeanalexromero280@outlook.es')
        ->send(new ContactanosMailable);
    return "Mensaje enviado correctamente";
})->name('contactanos');

//Route para enviar correos
Route::post('/vehiculo/sendMail', [VehiculoController::class, 'sendMail'])->name('vehiculo.mail');


