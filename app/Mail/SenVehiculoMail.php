<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SenVehiculoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.send-vehiculo')->with(['data' => $this->data])
            ->attach(storage_path('app/imagen/') . $this->data['imagenVehiculo'], [
                'as' => 'Vehiculo' . $this->data['imagenVehiculo'], //Nombre_nombrearchivoguardado ->'as' => 'imagen_vehiculo.' . $this->data['placa'] . '.' . $this->data['imagenVehiculo'],
                'mime' => 'imagen/' . $this->data['imagenVehiculo'],
            ])
            ->subject('Mail of cars'); //asunto de correo
    }
}
