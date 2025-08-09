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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique(); // LRN or Student ID
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable(); // Jr., Sr., III, etc.
            $table->date('birth_date');
            $table->enum('gender', ['Male', 'Female']);
            $table->text('address');
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();

            // Parent/Guardian Information
            $table->string('parent_guardian_name');
            $table->string('parent_guardian_contact');
            $table->string('parent_guardian_email')->nullable();
            $table->string('relationship_to_student')->default('Parent');

            // Academic Information
            $table->enum('grade_level', ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6']);
            $table->enum('status', ['Active', 'Inactive', 'Transferred', 'Graduated'])->default('Active');

            // Additional Information
            $table->text('medical_conditions')->nullable();
            $table->text('emergency_contact')->nullable();
            $table->date('enrollment_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
