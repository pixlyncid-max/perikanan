<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Stores VA number, QRIS string, or payment code from Xendit
            $table->text('payment_code')->nullable()->after('payment_url');
            // Stores the specific channel: BCA_VA, BNI_VA, MANDIRI_VA, BRI_VA, QRIS
            $table->string('payment_channel', 50)->nullable()->after('payment_code');
            // Stores when the payment expires
            $table->timestamp('payment_expires_at')->nullable()->after('payment_channel');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_code', 'payment_channel', 'payment_expires_at']);
        });
    }
};
