<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Program_Student;
use App\Models\Program_Teachar;
use App\Models\Publish;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            $table->string('path');
            $table->string('description')->nullable();
            $table->foreignIdFor(Program_Student::class,'program_student_id')->nullable();
            $table->foreignIdFor(Program_Teachar::class,'program_teacher_id')->nullable();
            $table->foreignIdFor(Publish::class,'publish_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
