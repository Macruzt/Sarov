<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReporteFinancierosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporte_financieros', function (Blueprint $table) {
            $table->id();
            $table->string('id_proyecto');
            $table->string('id_reporte');
            $table->string('total_fondos');
            $table->string('total_materiales_env');
            $table->string('tickets');
            $table->string('acomodacion');
            $table->string('taxis');
            $table->string('comida');
            $table->string('ayudas_participantes');
            $table->string('total_culto_ap_clau');
            $table->string('gastos_adicionales');
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
        Schema::dropIfExists('reporte_financieros');
    }
}
