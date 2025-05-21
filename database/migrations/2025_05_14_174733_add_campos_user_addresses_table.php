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
        Schema::table('user_addresses', function (Blueprint $table) {
            // Agregar campos nuevos
            $table->unsignedBigInteger('department_id')->nullable()->after('email');
            $table->string('department_name')->nullable()->after('department_id');
            $table->unsignedBigInteger('city_id')->nullable()->after('department_name');
            $table->string('city_name')->nullable()->after('city_id');

            // Eliminar campos antiguos si ya no los usarás
            $table->dropColumn(['country', 'state', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            // Revertir los cambios
            $table->dropColumn(['department_id', 'department_name', 'city_id', 'city_name']);

            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
        });
    }
};
