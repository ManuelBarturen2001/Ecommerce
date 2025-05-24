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
        Schema::create('culqi_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(0);
            $table->string('mode')->default('sandbox'); // sandbox o production
            $table->string('country_name')->default('Peru');
            $table->string('currency_name')->default('PEN');
            $table->string('currency_rate')->default('1');
            $table->string('public_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('culqi_settings');
    }
};
