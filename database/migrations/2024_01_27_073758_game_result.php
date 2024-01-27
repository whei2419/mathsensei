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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('random_tap');
            $table->string('hint_used');
            $table->string('wrong_try');
            $table->string('correct_try');
            $table->string('skip');
            $table->string('time_spent');

            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('question_id');

            $table->timestamps();

            $table->index(["game_id"], 'game-results');
            $table->index(["question_id"], 'results-questions');

            $table->foreign('game_id', 'game-results')
            ->references('id')->on('games')
            ->onDelete('restrict')
            ->onUpdate('restrict');

            $table->foreign('question_id', 'results-questions')
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
        //
    }
};
