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
        Schema::create('academies', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('address');
            $table->string('facebook_link')->nullable();
            $table->string('description')->nullable();
            $table->string('year');
            $table->string('resolve_brother')->default(0);
            $table->string('resolve_martyr')->default(0);
            $table->string('resolve_Son_teacher')->default(0);
            // $table->string('resolve_all')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academies');
    }
};
