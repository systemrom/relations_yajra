<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculoVendedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculo_vendedor', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('id_vehiculo')
                ->nullable()
                ->constrained('vehiculos')
                ->cascadeOnUpdate()
                ->nullOnDelete();
                
            $table->foreignId('id_vendedor')
                ->nullable()
                ->constrained('vendedors')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehiculo_vendedor');
    }
}
