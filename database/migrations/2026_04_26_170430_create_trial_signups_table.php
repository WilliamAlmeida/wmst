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
        if (Schema::hasTable('trial_signups')) {
            return;
        }

        Schema::create('trial_signups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 160);
            $table->string('company', 160)->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('message')->nullable();
            $table->string('preferred_locale', 5)->default('pt_BR');
            $table->string('product', 80)->nullable();
            $table->string('ai_agent_slug', 160)->nullable();
            $table->boolean('consent_accepted')->default(false);
            $table->json('metadata')->nullable();
            $table->foreignId('ai_agent_id')->nullable()->constrained('ai_agents')->nullOnDelete();
            $table->foreignId('agent_share_link_id')->nullable()->constrained('agent_share_links')->nullOnDelete();
            $table->timestamps();

            $table->index(['email', 'preferred_locale']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trial_signups');
    }
};
