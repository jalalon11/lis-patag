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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'created', 'updated', 'deleted'
            $table->string('model'); // 'Teacher', 'Guardian', 'Student', etc.
            $table->unsignedBigInteger('model_id');
            $table->string('title');
            $table->text('description');
            $table->string('icon');
            $table->unsignedBigInteger('user_id'); // Who performed the action
            $table->json('changes')->nullable(); // Store what changed
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['created_at', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
