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
        Schema::table('orders', function (Blueprint $table) {
            // Drop the old foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Add user_type for polymorphic relation
            $table->string('user_type')->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('user_type');
            
            // Restore the foreign key constraint (if needed, but might fail if data is invalid)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
