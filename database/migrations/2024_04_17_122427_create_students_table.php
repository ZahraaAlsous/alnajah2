<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Classs;
use App\Models\Section;
use App\Models\Parentt;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('student_type')->default(0);
            $table->integer('calssification')->nullable();
            $table->double('school_tuition');
            $table->foreignIdfor(User::class,'user_id');
            $table->foreignIdfor(Classs::class,'class_id');
            $table->foreignIdfor(Section::class,'section_id');
            $table->foreignIdfor(Parentt::class,'parentt_id');
            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
