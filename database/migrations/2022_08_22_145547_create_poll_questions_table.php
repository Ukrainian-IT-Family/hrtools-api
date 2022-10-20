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
        Schema::create('poll_questions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('required')->default(0);

            $table->foreignId('type')->constrained('question_types');
            $table->foreignId('poll_id')->constrained('polls')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('poll_questions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('type');
            $table->dropConstrainedForeignId('poll_id');
        });
        Schema::dropIfExists('poll_questions');
    }
};
