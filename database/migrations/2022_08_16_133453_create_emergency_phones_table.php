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
        Schema::create('emergency_phones', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->timestamps();

            $table->foreignId('emergency_contact_id')->nullable()->constrained('emergency_contacts')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emergency_phones', function (Blueprint $table) {
            $table->dropConstrainedForeignId('emergency_contact_id');
        });
        Schema::dropIfExists('emergency_phones');
    }
};
