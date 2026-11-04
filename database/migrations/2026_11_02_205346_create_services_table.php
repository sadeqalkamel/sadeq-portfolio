<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {

            $table->id();

            // English content
            $table->string('title_en', 150);
            $table->text('description_en');

            // Arabic content
            $table->string('title_ar', 150)->nullable();
            $table->text('description_ar')->nullable();

            // Service icon
            $table->string('icon')->nullable();

            // Display settings
            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->boolean('is_active')
                ->default(true);

            // Creation and update dates
            $table->timestamps();

            // Optimize active services ordering
            $table->index([
                'is_active',
                'sort_order',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};