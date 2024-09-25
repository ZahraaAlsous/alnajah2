<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->string('quostion');
            $table->string('year');
            $table->integer('state_on_off')->default(1);//0->off 1->on
            $table->foreignIdFor(Section::class,'section_id');
            $table->foreignIdFor(Subject::class,'subject_id');
            $table->foreignIdFor(Teacher::class,'teacher_id');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
