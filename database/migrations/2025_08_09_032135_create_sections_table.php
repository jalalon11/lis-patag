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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_name'); // e.g., "A", "B", "Mabait", "Masipag"
            $table->enum('grade_level', ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']);
            $table->foreignId('school_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('adviser_id')->nullable()->constrained('users')->onDelete('set null'); // Teacher as adviser
            $table->integer('capacity')->default(40);
            $table->integer('current_enrollment')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['section_name', 'grade_level', 'school_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
