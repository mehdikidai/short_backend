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
        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('url_id')->constrained('urls')->onUpdate('cascade')->onDelete('cascade');
            $table->string('ip_address');
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->float('lat')->nullable();
            $table->float('lon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};
