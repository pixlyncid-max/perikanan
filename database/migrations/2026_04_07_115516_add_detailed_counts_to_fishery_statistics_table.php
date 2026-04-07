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
        Schema::table('fishery_statistics', function (Blueprint $table) {
            $table->integer('shrimp_farmer_count')->nullable()->after('fish_farmer_count');
            $table->integer('fisherman_count')->nullable()->after('shrimp_farmer_count');
            
            // "Pembudidaya Lainnya" detailed categories
            $table->integer('crab_farmer_count')->nullable()->after('main_commodities');
            $table->integer('seaweed_farmer_count')->nullable()->after('crab_farmer_count');
            $table->integer('clam_farmer_count')->nullable()->after('seaweed_farmer_count');
            $table->integer('lobster_farmer_count')->nullable()->after('clam_farmer_count');
            $table->integer('abalone_farmer_count')->nullable()->after('lobster_farmer_count');
            $table->integer('sea_cucumber_farmer_count')->nullable()->after('abalone_farmer_count');
            $table->integer('other_farmer_count')->nullable()->after('sea_cucumber_farmer_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fishery_statistics', function (Blueprint $table) {
            $table->dropColumn([
                'shrimp_farmer_count',
                'fisherman_count',
                'crab_farmer_count',
                'seaweed_farmer_count',
                'clam_farmer_count',
                'lobster_farmer_count',
                'abalone_farmer_count',
                'sea_cucumber_farmer_count',
                'other_farmer_count',
            ]);
        });
    }
};
