<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Employee;
use App\Models\Teacher;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('out_of_work_employees', function (Blueprint $table) {
        $table->id();
        $table->date('date');
        $table->integer('num_hour_out');
        $table->string('note')->nullable();
        $table->foreignIdFor(Employee::class, 'employee_id')->nullable();
        $table->foreignIdFor(Teacher::class, 'teacher_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('out_of_work_employees');
    }
};
