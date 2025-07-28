<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioComprasTable extends Migration
{
    public function up()
    {
        Schema::create('inventario_compras', function (Blueprint $table) {
            $table->id();
            
            // Información básica del producto
            $table->string('codigo_producto')->unique();
            $table->string('marca');
            $table->string('referencia');
            $table->text('detalle_producto');
            $table->string('unidad_medida');
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(1);
            $table->string('ubicacion')->nullable();
            $table->string('estado')->default('activo'); // activo, inactivo
            
            // Información de la última compra
            $table->string('numero_factura')->nullable();
            $table->date('fecha_factura')->nullable();
            $table->string('proveedor')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->integer('cantidad_comprada')->default(0);
            $table->decimal('valor_unitario', 10, 2)->default(0);
            $table->integer('iva_porcentaje')->default(0);
            $table->decimal('valor_iva', 10, 2)->default(0);
            $table->decimal('total_compra', 10, 2)->default(0);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventario_compras');
    }
}