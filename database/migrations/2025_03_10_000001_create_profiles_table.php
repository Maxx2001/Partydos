<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('display_name', 40);
            $table->string('tagline', 80)->nullable();
            $table->text('bio')->nullable();
            $table->string('city', 60)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('website_url', 255)->nullable();
            $table->string('instagram_url', 255)->nullable();
            $table->string('tiktok_url', 255)->nullable();
            $table->string('spotify_url', 255)->nullable();
            $table->json('interests')->nullable();
            $table->json('favorite_music')->nullable();
            $table->json('party_style_tags')->nullable();
            $table->string('privacy_profile')->default('public');
            $table->string('privacy_guestbook')->default('public');
            $table->string('privacy_photos')->default('friends');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
