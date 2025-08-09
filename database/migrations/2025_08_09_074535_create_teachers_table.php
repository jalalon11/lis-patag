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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_id')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->date('birth_date');
            $table->enum('gender', ['Male', 'Female']);
            $table->text('address');
            $table->string('contact_number');
            $table->string('email')->unique();
            $table->string('emergency_contact');
            $table->string('position');
            $table->string('department')->nullable();
            $table->date('hire_date');
            $table->enum('employment_status', ['Active', 'Inactive', 'Resigned', 'Terminated'])->default('Active');
            $table->decimal('salary', 10, 2)->nullable();
            $table->text('qualifications')->nullable();
            $table->text('certifications')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
