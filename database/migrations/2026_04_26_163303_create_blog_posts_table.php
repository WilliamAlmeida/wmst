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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('blog_category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->string('locale', 5)->default('pt_BR');
            $table->string('title', 180);
            $table->string('slug', 180);
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('status', 20)->default('draft');
            $table->string('featured_image_path')->nullable();
            $table->string('seo_title', 180)->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_og_image_path')->nullable();
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['locale', 'slug']);
            $table->index(['status', 'published_at']);
            $table->index('scheduled_for');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
