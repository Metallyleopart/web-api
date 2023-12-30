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
        Schema::create('remedials', function (Blueprint $table) {
            $table->id();
            $table->integer('nilai');
            $table->integer('nilai_gabungan');
            $table->foreignId('tasks_id')->references('id')->on('tasks')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remedials');
    }
};
