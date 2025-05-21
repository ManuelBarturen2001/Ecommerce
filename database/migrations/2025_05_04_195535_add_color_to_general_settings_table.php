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
        Schema::table('general_settings', function (Blueprint $table) {
            $table->string('color')->default('#950D0D'); // valor por defecto
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
