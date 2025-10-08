<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->string('movement_type'); // 'entrada' o 'salida'
            $table->integer('quantity');
            $table->string('reference_type');
            $table->bigInteger('reference_id')->unsigned();
            $table->text('description')->nullable();
            $table->date('movement_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
