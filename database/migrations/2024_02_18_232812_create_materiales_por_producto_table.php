<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialesPorProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materiales_por_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('material_id');
            $table->double('cantidad',8,3)->default('0')->nullable();
            $table->timestamps();

            $table->foreign('producto_id')
                ->references('id')
                ->on('productos')
                ->onDelete('cascade');
                
            $table->foreign('material_id')
                ->references('id')
                ->on('materiales')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materiales_por_producto', function (Blueprint $table) {
            // Eliminar la restricción de clave externa
            $table->dropForeign(['producto_id']);
            $table->dropForeign(['material_id']);
            // Eliminar la columna
            $table->dropColumn('producto_id');
            $table->dropColumn('material_id');
        });
        Schema::dropIfExists('materiales_por_producto');
    }
}
