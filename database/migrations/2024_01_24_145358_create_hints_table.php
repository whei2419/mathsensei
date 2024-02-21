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
        Schema::create('hints', function (Blueprint $table) {
            $table->id();
            $table->string('left');
            $table->string('right');
            $table->unsignedBigInteger('question_id');
            $table->string('solution_number');
            $table->string('text');

            $table->timestamps();

            $table->index(["question_id"], 'questions-hints');

            $table->foreign('question_id', 'questions-hints')
            ->references('id')->on('questions')
            ->onDelete('restrict')
            ->onUpdate('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hints');
    }
};
