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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('tugas');
            $table->integer('nilai')->nullable();
            $table->text('status_nilai')->nullable();
            $table->foreignId('student_id')->nullable()->references('user_id')->on('students')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->references('user_id')->on('teachers')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
