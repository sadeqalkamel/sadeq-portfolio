<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {

            // Arabic project title
            $table->string('title_ar')->nullable();

            // Arabic short description
            $table->text('short_description_ar')->nullable();

            // Arabic full description
            $table->longText('description_ar')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'title_ar',
                'short_description_ar',
                'description_ar',
            ]);
        });
    }
};