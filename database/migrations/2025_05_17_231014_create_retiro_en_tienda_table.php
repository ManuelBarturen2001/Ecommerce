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
        Schema::create('retiro_en_tienda', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_tienda');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('telefono')->nullable();
            $table->string('horario_apertura')->nullable();
            $table->string('horario_cierre')->nullable();
            $table->string('dias_disponibles')->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->text('instrucciones_retiro')->nullable();
            $table->text('documentos_requeridos')->nullable();
            $table->boolean('estado')->default(true);
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retiro_en_tienda');
    }
};
