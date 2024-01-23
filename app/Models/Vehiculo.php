<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = ['placa', 'peso', 'paquete', 'volumen', 'id_compania'];
    
    public function companias(){
        return $this->belongsTo(Companias::class, 'id_compania');
    }

    public function vendedor(){
        return $this->hasOne(Vendedor::class, 'id_vehiculo');
    }

}
