<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_agents', function (Blueprint $table): void {
            $table->unsignedInteger('max_interactions')->default(20)->after('metadata');
            $table->unsignedInteger('max_message_length')->default(500)->after('max_interactions');
        });

        Schema::create('agent_chat_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ai_agent_id')->constrained('ai_agents')->cascadeOnDelete();
            $table->foreignId('agent_share_link_id')->constrained('agent_share_links')->cascadeOnDelete();
            $table->string('session_token', 64)->unique();
            $table->string('visitor_name', 120);
            $table->string('visitor_phone', 32);
            $table->text('visitor_reason');
            $table->string('screening_status', 30)->default('approved');
            $table->text('screening_notes')->nullable();
            $table->string('status', 20)->default('active');
            $table->unsignedInteger('interactions_count')->default(0);
            $table->unsignedBigInteger('prompt_tokens')->default(0);
            $table->unsignedBigInteger('completion_tokens')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index(['ai_agent_id', 'status']);
        });

        Schema::create('agent_chat_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('agent_chat_session_id')->constrained('agent_chat_sessions')->cascadeOnDelete();
            $table->string('role', 20);
            $table->longText('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_chat_messages');
        Schema::dropIfExists('agent_chat_sessions');

        Schema::table('ai_agents', function (Blueprint $table): void {
            $table->dropColumn(['max_interactions', 'max_message_length']);
        });
    }
};
