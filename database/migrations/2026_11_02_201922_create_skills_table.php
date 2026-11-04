<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {

            $table->id();

            // Skill names
            $table->string('name_en', 120);
            $table->string('name_ar', 120)->nullable();

            // Skill category
            // backend, frontend, tools
            $table->string('category', 30);

            // Icon image path
            $table->string('icon')->nullable();

            // Display settings
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            // Timestamps
            $table->timestamps();

            // Improve filtering and ordering
            $table->index([
                'category',
                'is_active',
                'sort_order',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};