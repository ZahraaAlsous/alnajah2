<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\Subject;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->integer('ponus')->nullable();
            $table->integer('homework')->nullable();
            $table->integer('oral')->nullable();
            $table->integer('test1')->nullable();
            $table->integer('test2')->nullable();
            $table->integer('exam_med')->nullable();
            $table->integer('exam_final')->nullable();
            $table->boolean('state')->default(0);
            $table->foreignIdFor(Student::class, 'student_id');
            $table->foreignIdFor(Subject::class, 'subject_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
