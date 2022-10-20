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
        Schema::create('vacation_or_hospitals', function (Blueprint $table) {
            $table->id();
            $table->boolean('type');
            $table->date('date_start');
            $table->date('date_end');
            $table->integer('days_count')->unsigned();
            $table->tinyInteger('status')->nullable()->default(null);
            $table->string('comment')->nullable();

            $table->timestamps();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacation_or_hospitals');
    }
};
