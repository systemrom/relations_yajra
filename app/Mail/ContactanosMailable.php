<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;


class ContactanosMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    //Metodo para establecer datos para el correo
    public  function envelope()
    {
        $from = [
            'address'=>'jeanalexromero280@outlook.es',
            'name'=>'Alex Romero'
        ];

        return $this->from($from['address'], $from['name'])
            ->subject("Informacion de Contacto");
    }

    public function build()
    {
        return $this->envelope()->view('email.contactanos');
    }

}
