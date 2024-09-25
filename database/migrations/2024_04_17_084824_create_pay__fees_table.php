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
        Schema::create('pay__fees', function (Blueprint $table) {
            $table->id();

            $table->string('type')->nullable();
            $table->date('date');
            $table->double('amount_money');
            $table->double('remaining_fee');
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
        Schema::dropIfExists('pay__fees');
    }
};
