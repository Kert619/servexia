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
        Schema::create('users_info', function (Blueprint $table) {
            $table->id('user_info_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_info_first_name', 100);
            $table->string('user_info_last_name', 100);
            $table->string('user_info_phone', 100)->nullable();
            $table->string('user_info_address', 500)->nullable();
            $table->string('user_info_region')->nullable();
            $table->string('user_info_province')->nullable();
            $table->string('user_info_city')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('user_id')->on('users');
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_info');
    }
};
