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
        Schema::create('remidials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('task_id')->references('id')->on('tasks')->constrained()->cascadeOnDelete();
            $table->integer('nilai_awal')->nullable();
            $table->integer('nilai_remidial')->nullable();
            $table->integer('nilai_akhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remidials');
    }
};
