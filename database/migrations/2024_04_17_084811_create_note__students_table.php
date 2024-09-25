<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Student;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.   
     */
    public function up(): void
    {
        Schema::create('note__students', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('text');
            $table->foreignIdFor(Student::class,'student_id');
            $table->foreignIdFor(User::class,'user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('note__students');
    }
};
