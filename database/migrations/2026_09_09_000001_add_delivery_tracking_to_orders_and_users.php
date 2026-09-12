<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer')->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('driver_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->decimal('delivery_latitude', 10, 7)->nullable()->after('delivery_address');
            $table->decimal('delivery_longitude', 10, 7)->nullable()->after('delivery_latitude');
            $table->decimal('driver_latitude', 10, 7)->nullable()->after('delivery_longitude');
            $table->decimal('driver_longitude', 10, 7)->nullable()->after('driver_latitude');
            $table->timestamp('driver_location_updated_at')->nullable()->after('driver_longitude');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn([
                'driver_id',
                'delivery_latitude',
                'delivery_longitude',
                'driver_latitude',
                'driver_longitude',
                'driver_location_updated_at',
            ]);
        });

    }
};
