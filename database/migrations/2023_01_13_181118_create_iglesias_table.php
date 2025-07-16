<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIglesiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iglesias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('nombre_pastor');
            $table->string('lider_encargado');
            $table->string('cant_miembros');
            $table->string('direccion');
            $table->enum('pais',['Argentina','Bolivia','Brasil','Chile','Colombia','Costa Rica','Cuba','Estados Unidos','Ecuador','El Salvador','Guatemala','Honduras','México','Nicaragua','Paraguay','Panamá','Perú','Uruguay','Venezuela']);
            $table->string('region');
            $table->string('num_contacto');
            $table->string('miembros_oa');
            $table->string('missioneros_req');
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
        Schema::dropIfExists('iglesias');
    }
}
