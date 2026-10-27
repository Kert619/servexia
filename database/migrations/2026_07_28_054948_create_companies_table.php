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
        Schema::create('companies', function (Blueprint $table) {
            $table->id('company_id');
            $table->string('company_name')->unique();
            $table->string('company_description', 500)->nullable();
            $table->string('company_email', 100)->nullable();
            $table->string('company_phone', 100)->nullable();
            $table->unsignedBigInteger('company_owner_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_owner_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
