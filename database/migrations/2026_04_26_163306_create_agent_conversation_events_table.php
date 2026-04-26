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
        if (Schema::hasTable('agent_conversation_events')) {
            return;
        }

        Schema::create('agent_conversation_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_share_link_id')->constrained('agent_share_links')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event_name', 80);
            $table->timestamp('event_at');
            $table->string('external_event_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['agent_share_link_id', 'event_name']);
            $table->index('event_at');
            $table->index('external_event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_conversation_events');
    }
};
