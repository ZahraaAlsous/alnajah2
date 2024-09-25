<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('out__of__work__students', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->string('justification')->nullable();
            $table->foreignIdFor(Student::class,'student_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('out__of__work__students');
    }
};
