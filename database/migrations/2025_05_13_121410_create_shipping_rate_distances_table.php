<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('shipping_rate_distances', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('shipping_rule_id'); // Relación con Shipping_rules
        $table->decimal('min_km', 8, 2); // Mínima distancia (en km)
        $table->decimal('max_km', 8, 2); // Máxima distancia (en km)
        $table->decimal('price', 10, 2); // Precio para ese rango
        $table->timestamps();

        // Clave foránea
        $table->foreign('shipping_rule_id')->references('id')->on('shipping_rules')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rate_distances');
    }
};
