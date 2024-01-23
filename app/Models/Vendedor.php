<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;


    //El filleble -> es un método de Eloquent que al hacer un registro solo abarca estos campos.
    protected $fillable = ['nombre', 'codigo', 'usuario', 'contraseña', 'id_compania'];

    public function companias(){
        return $this->belongsTo(Companias::class, 'id_compania');
    }

    public function vehiculo(){
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }

}
