<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan nilai 'completed' ke ENUM status pada tabel orders.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('pending','processing','shipped','delivered','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status` ENUM('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
