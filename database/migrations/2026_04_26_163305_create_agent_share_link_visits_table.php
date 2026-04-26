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
        if (Schema::hasTable('agent_share_link_visits')) {
            return;
        }

        Schema::create('agent_share_link_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_share_link_id')->constrained('agent_share_links')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->timestamp('visited_at');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['agent_share_link_id', 'visited_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_share_link_visits');
    }
};
