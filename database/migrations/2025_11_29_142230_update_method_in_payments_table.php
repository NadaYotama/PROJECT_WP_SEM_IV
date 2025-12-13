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
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('method', ['cash', 'transfer_bca', 'transfer_mandiri', 'transfer_bni', 'gopay', 'ovo', 'dana', 'shopeepay', 'credit_card', 'midtrans'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('method', ['cash', 'transfer_bca', 'transfer_mandiri', 'transfer_bni', 'gopay', 'ovo', 'dana', 'shopeepay', 'credit_card'])->change();
        });
    }
};
