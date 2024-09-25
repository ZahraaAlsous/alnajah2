<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Classs;
use App\Models\Teacher;
use App\Models\Section;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_sections', function (Blueprint $table) {
            $table->id();

            $table->foreignIdfor(Section::class,'section_id');
            $table->foreignIdfor(Teacher::class,'teacher_id');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_sections');
    }
};
