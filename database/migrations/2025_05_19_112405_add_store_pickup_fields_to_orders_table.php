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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_store_pickup')->default(false);
            $table->unsignedBigInteger('store_pickup_id')->nullable();
            
            // Si quieres crear una relación con la tabla retiro_tienda
            $table->foreign('store_pickup_id')->references('id')->on('retiro_en_tienda')->onDelete('set null');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['store_pickup_id']);
            $table->dropColumn(['is_store_pickup', 'store_pickup_id']);
        });
    }
};
