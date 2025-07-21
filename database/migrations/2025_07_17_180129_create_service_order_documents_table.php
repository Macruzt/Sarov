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
            
            // Campos para guardar PDFs en base64 (LONGTEXT para archivos grandes)
            $table->longText('reception_signed')->nullable(); // PDF de recepción firmada en base64
            $table->longText('delivery_signed')->nullable(); // PDF de acta firmada en base64
            
            // Metadata para los documentos
            $table->string('reception_filename')->nullable(); // Nombre del archivo de recepción
            $table->string('delivery_filename')->nullable(); // Nombre del archivo de acta
            $table->integer('reception_size')->nullable(); // Tamaño en bytes del PDF de recepción
            $table->integer('delivery_size')->nullable(); // Tamaño en bytes del PDF de acta
            
            // Timestamps de cuando se firmaron los documentos
            $table->timestamp('reception_signed_at')->nullable(); // Cuándo se firmó la recepción
            $table->timestamp('delivery_signed_at')->nullable(); // Cuándo se firmó el acta
            
            $table->timestamps(); // created_at y updated_at
            
            // Índices para mejorar rendimiento
            $table->index('order_id');
            $table->index('reception_signed_at');
            $table->index('delivery_signed_at');
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