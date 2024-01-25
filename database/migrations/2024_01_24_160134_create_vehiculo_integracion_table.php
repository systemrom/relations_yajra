<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculoIntegracionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculo_integracion', function (Blueprint $table) {
            $table->id();

            //Llaves foraneas
            $table->unsignedBigInteger('id_vehiculo');
            $table->string('id_integracion');

            $table->foreign('id_vehiculo')->references('id')->on('vehiculos');

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
        Schema::dropIfExists('vehiculo_integracion');
    }
}
