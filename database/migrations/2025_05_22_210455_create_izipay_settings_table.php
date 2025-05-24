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
        Schema::create('izipay_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(0);
            $table->enum('mode', ['sandbox', 'live'])->default('sandbox');
            $table->string('country_name')->default('Peru');
            $table->string('currency_name')->default('PEN');
            $table->decimal('currency_rate', 8, 2)->default(1.00);
            $table->string('shop_id')->nullable();
            $table->string('public_key')->nullable();
            $table->string('private_key')->nullable();
            $table->string('hmac_sha256_key')->nullable();
            $table->string('javascript_client_key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izipay_settings');
    }
};
