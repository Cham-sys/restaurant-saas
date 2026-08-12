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
        Schema::table('themes', function (Blueprint $table) {
            if (! Schema::hasColumn('themes', 'author')) {
                $table->string('author')->nullable()->after('description');
            }

            if (! Schema::hasColumn('themes', 'version')) {
                $table->string('version')->default('1.0.0')->after('author');
            }

            if (! Schema::hasColumn('themes', 'sections')) {
                $table->json('sections')->nullable()->after('version');
            }

            if (! Schema::hasColumn('themes', 'settings')) {
                $table->json('settings')->nullable()->after('sections');
            }
        });

        Schema::table('restaurants', function (Blueprint $table) {
            if (! Schema::hasColumn('restaurants', 'theme_settings')) {
                $table->json('theme_settings')->nullable()->after('theme_id');
            }

            if (! Schema::hasColumn('restaurants', 'custom_sections')) {
                $table->json('custom_sections')->nullable()->after('theme_settings');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            if (Schema::hasColumn('themes', 'author')) {
                $table->dropColumn('author');
            }

            if (Schema::hasColumn('themes', 'version')) {
                $table->dropColumn('version');
            }

            if (Schema::hasColumn('themes', 'sections')) {
                $table->dropColumn('sections');
            }

            if (Schema::hasColumn('themes', 'settings')) {
                $table->dropColumn('settings');
            }
        });

        Schema::table('restaurants', function (Blueprint $table) {
            if (Schema::hasColumn('restaurants', 'theme_settings')) {
                $table->dropColumn('theme_settings');
            }

            if (Schema::hasColumn('restaurants', 'custom_sections')) {
                $table->dropColumn('custom_sections');
            }
        });
    }
};
