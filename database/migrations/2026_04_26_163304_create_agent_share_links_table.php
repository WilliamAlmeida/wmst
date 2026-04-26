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
        if (Schema::hasTable('agent_share_links')) {
            return;
        }

        Schema::create('agent_share_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_agent_id')->constrained('ai_agents')->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('token', 80)->unique();
            $table->string('label', 120)->nullable();
            $table->string('expiry_type', 30)->default('fixed_datetime');
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('expires_in_minutes')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->string('usage_type', 20)->default('multi_use');
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('first_accessed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['ai_agent_id', 'revoked_at']);
            $table->index('issued_at');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_share_links');
    }
};
