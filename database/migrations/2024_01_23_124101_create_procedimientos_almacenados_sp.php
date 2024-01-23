<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProcedimientosAlmacenadosSp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Schema::create('procedimientos_almacenados_sp', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        }); */
        $proced1 = "
                        CREATE PROCEDURE insertarCompanias(
                            IN c_nombre VARCHAR(60)
                        )
                        BEGIN 
                            INSERT INTO companias (nombre)
                            VALUES (c_nombre);
                        END;
                    ";
        DB::unprepared($proced1);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* Schema::dropIfExists('procedimientos_almacenados_sp'); */
        $proced1 = "DROP PROCEDURE IF EXISTS insertarCompanias";
        DB::unprepared($proced1);
    }
}
