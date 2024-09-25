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
        Schema::create('salary', function (Blueprint $table) {
            $table->id();
            $table->double('salary_of_teacher');
            $table->double('num_houre')->default(0);
            $table->date('month');
            $table->string('year');
            $table->foreignIdFor(Teacher::class,'teacher_id')->nullable();
            $table->foreignIdFor(Teacher::class,'employee_id')->nullable();
            $table->tinyInteger('status')->default(0);//استلم المعاش = 1 ، لم يستلم المعاش = 0;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary');
    }
};
