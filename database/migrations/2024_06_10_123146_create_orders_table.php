<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\Course;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('birthday')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->boolean('classification')->default(0);
            $table->string('class')->nullable();
            $table->string('year')->nullable();
            $table->tinyInteger('student_type')->nullable();
            $table->foreignIdFor(Student::class,'student_id')->nullable();
            $table->foreignIdFor(Course::class,'course_id')->nullable();

            $table->timestamps();


            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
