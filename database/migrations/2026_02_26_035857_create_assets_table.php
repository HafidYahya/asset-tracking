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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_asset_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('gps_number')->unique()->nullable();
            $table->string('condition');
            $table->date('purchase_date');
            $table->decimal('purchase_price', 15, 2);
            $table->date('warranty_expired')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};