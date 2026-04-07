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
        Schema::create('fishery_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('regency_city');
            $table->integer('year');
            $table->integer('fish_farmer_count')->nullable();
            $table->double('production_volume')->nullable();
            $table->decimal('production_value', 20, 2)->nullable();
            $table->double('area_size')->nullable();
            $table->string('main_commodities')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fishery_statistics');
    }
};
