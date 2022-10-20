<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_answer_poll_result', function (Blueprint $table) {
            $table->primary(['poll_result_id', 'poll_answer_id']);

            $table->foreignId('poll_result_id')->constrained('poll_results')->cascadeOnDelete();
            $table->foreignId('poll_answer_id')->constrained('poll_answers')->cascadeOnDelete();

            $table->index('poll_result_id');
            $table->index('poll_answer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poll_answer_poll_result');
    }
};
