<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad_inventario')->default(0);
            $table->date('fecha_agregado');
            $table->date('fecha_vencimiento');
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('productos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
