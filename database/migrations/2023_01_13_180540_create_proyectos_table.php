<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('Lema_proyecto');
            $table->string('info_proyecto');
            $table->string('inicio_proyecto');
            $table->string('termino_proyecto');
            $table->enum('pais',['Argentina','Bolivia','Brasil','Chile','Colombia','Costa Rica','Cuba','Estados Unidos','Ecuador','El Salvador','Guatemala','Honduras','México','Nicaragua','Paraguay','Panamá','Perú','Uruguay','Venezuela']);
            $table->enum('tipo_proyecto', ['N2N', 'INTL', 'OA','N2N-General','N2N-Hombres','N2N-Mujeres','N2N-Niños']);
            $table->string('region');
            $table->string('ciudad');
            $table->string('num_iglesias');
            $table->string('num_pers_escucharan');
            $table->string('num_desiciones');
            $table->string('num_per_disciples');
            $table->string('num_per_bautizadas');
            $table->string('nombre_lider');
            $table->string('email_lider');
            $table->string('contacto_lider');
            $table->string('primer_proyecto');
            $table->string('necesidades');
            $table->string('metas_proyecto');
            $table->string('id_usuario');
            $table->string('id_presupuesto');
            $table->string('status');
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
        Schema::dropIfExists('proyectos');
    }
}
