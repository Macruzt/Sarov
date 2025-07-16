<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->enum('pais',['Argentina','Bolivia','Brasil','Chile','Colombia','Costa Rica','Cuba','Estados Unidos','Ecuador','El Salvador','Guatemala','Honduras','México','Nicaragua','Paraguay','Panamá','Perú','Uruguay','Venezuela']);
            $table->string('localidad');
            $table->string('fecha');
            $table->string('descripcion');
            $table->string('num_iglesias');
            $table->string('num_missioneros');
            $table->string('num_mission_eeuu');
            $table->string('num_pers_escucha');
            $table->string('num_profes_fe');
            $table->string('num_pers_recon');
            $table->string('num_bautismos');
            $table->string('num_iglesias_plant');
            $table->string('testimonio_1');
            $table->string('testimonio_2');
            $table->string('fotos');
            $table->string('id_usuario');
            $table->string('id_proyecto');
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
        Schema::dropIfExists('reportes');
    }
}
