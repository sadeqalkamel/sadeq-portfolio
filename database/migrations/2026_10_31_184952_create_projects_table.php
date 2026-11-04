<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Basic project information
            $table->string('title');
            $table->string('slug')->unique();

            // Descriptions
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Project image
            $table->string('cover_image')->nullable();

            // Technologies used
            $table->json('technologies')->nullable();

            // Project links
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();

            // Display settings
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};