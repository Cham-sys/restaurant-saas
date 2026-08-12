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
            if (! Schema::hasColumn('orders', 'tracking_code')) {
                $table->string('tracking_code')->unique()->nullable(); // رمز التتبع
            }

            if (! Schema::hasColumn('orders', 'final_amount')) {
                $table->decimal('final_amount', 10, 2)->default(0); // المبلغ النهائي
            }

            if (! Schema::hasColumn('orders', 'payment_method')) {
                $table->enum('payment_method', ['cash', 'transfer'])->default('cash');
            }

            if (! Schema::hasColumn('orders', 'payment_proof')) {
                $table->string('payment_proof')->nullable();
            }

            if (! Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
