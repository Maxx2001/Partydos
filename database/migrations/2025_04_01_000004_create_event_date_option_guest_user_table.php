<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_date_option_guest_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_date_option_id')->constrained('event_date_options')->onDelete('cascade');
            $table->foreignId('guest_user_id')->constrained('guest_users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_date_option_guest_user');
    }
};
