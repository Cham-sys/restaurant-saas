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
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('number', 50);
            $table->string('name')->nullable();
            $table->unsignedSmallInteger('seats')->default(2);
            $table->string('qr_token', 32)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['restaurant_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
