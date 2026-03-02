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
        Schema::create('asset_locations', function (Blueprint $table) {
            $table->id();
            $table->string('gps_number');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->timestamp('uploadtime')->nullable();
            $table->integer('electricity')->nullable();
            $table->timestamp('timestamp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_locations');
    }
};