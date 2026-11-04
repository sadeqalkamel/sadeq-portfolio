<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // English and Arabic names
            $table->string('first_name_en', 100);
            $table->string('last_name_en', 100);
            $table->string('first_name_ar', 100)->nullable();
            $table->string('last_name_ar', 100)->nullable();

            // Professional information
            $table->string('job_title_en', 180)->nullable();
            $table->string('job_title_ar', 180)->nullable();

            $table->text('hero_description_en')->nullable();
            $table->text('hero_description_ar')->nullable();

            $table->longText('about_en')->nullable();
            $table->longText('about_ar')->nullable();

            // Profile image and CV
            $table->string('photo')->nullable();
            $table->string('cv_file')->nullable();

            // Contact information
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('location_en')->nullable();
            $table->string('location_ar')->nullable();

            // Social links
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('x_url')->nullable();

            // Statistics
            $table->decimal('experience_years', 4, 1)->nullable();
            $table->unsignedInteger('completed_projects')->nullable();
            $table->unsignedInteger('happy_clients')->nullable();
            $table->boolean('show_statistics')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};