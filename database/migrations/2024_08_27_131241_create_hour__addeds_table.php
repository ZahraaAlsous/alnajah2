<?php

use App\Models\Teacher;
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
        Schema::create('hour__addeds', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Teacher::class,'teacher_id');
            $table->integer('num_hour_added')->default(0);
            $table->string('note_hour_added')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hour__addeds');
    }
};
