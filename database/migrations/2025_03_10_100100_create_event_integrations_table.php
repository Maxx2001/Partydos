<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('provider');
            $table->string('external_calendar_id');
            $table->string('external_event_id');
            $table->boolean('sync_enabled')->default(true);
            $table->dateTime('last_synced_at')->nullable();
            $table->string('last_sync_hash')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_integrations');
    }
};
