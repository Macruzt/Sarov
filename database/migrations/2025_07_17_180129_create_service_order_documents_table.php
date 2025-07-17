<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceOrderDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_order_documents', function (Blueprint $table) {
            $table->id(); // Llave primaria autoincremental
            $table->integer('order_id'); // ID de la orden de servicio
            $table->string('reception_signed', 255)->nullable(); // Recepción firmada
            $table->string('delivery_signed', 255)->nullable(); // Acta firmada
            $table->timestamps(); // created_at y updated_at
            
            // Índices para mejorar rendimiento
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_order_documents');
    }
}