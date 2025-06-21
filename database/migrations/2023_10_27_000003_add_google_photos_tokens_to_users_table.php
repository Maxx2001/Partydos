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
        Schema::table('users', function (Blueprint $table) {
            $table->text('google_photos_access_token')->nullable()->after('remember_token');
            $table->text('google_photos_refresh_token')->nullable()->after('google_photos_access_token');
            $table->timestamp('google_photos_token_expires_at')->nullable()->after('google_photos_refresh_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_photos_access_token',
                'google_photos_refresh_token',
                'google_photos_token_expires_at',
            ]);
        });
    }
};
