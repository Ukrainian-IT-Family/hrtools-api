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
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image')->nullable();
            $table->string('middle_name')->nullable();
            $table->date('birthday')->nullable();
            $table->boolean('gender')->nullable();
            $table->string('region')->nullable();
            $table->string('area')->nullable();
            $table->string('town')->nullable();
            $table->text('post_office')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();

            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('marital_status_id')->nullable()->constrained('marital_statuses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_image');
            $table->dropColumn('middle_name');
            $table->dropColumn('birthday');
            $table->dropColumn('gender');
            $table->dropColumn('region');
            $table->dropColumn('area');
            $table->dropColumn('town');
            $table->dropColumn('post_office');
            $table->dropColumn('linkedin');
            $table->dropColumn('facebook');

            $table->dropConstrainedForeignId('manager_id');
            $table->dropConstrainedForeignId('marital_status_id');
        });
    }
};
