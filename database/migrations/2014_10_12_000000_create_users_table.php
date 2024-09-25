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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->date('birthday');
            $table->tinyInteger('gender');
            $table->string('phone');
            $table->string('address');
            $table->string('year');
            $table->string('image')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('conf_password');
            $table->string('user_type');
            $table->string('fcm_token')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
