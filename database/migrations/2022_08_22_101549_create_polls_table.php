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
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('status')->default(1);
            $table->boolean('anonymous')->default(0);

            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
        });
        Schema::dropIfExists('polls');
    }
};
