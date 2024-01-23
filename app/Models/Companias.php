<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companias extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function vehiculos(){
        return $this->hasMany(Vehiculo::class, 'id_compania');
    }

    public function vendedores(){
        return $this->hasMany(Vendedor::class, 'id_compania');
    }

}
