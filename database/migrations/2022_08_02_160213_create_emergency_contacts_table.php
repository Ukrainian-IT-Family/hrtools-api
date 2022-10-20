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
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->timestamps();

            $table->foreignId('relationship')->constrained('relationships');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('phones')->constrained('phones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emergency_contacts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('relationship');
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('phones');
        });
        Schema::dropIfExists('emergency_contacts');
    }
};
