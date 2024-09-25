<?php

use App\Models\Employee;
use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('create__maturitie_tables', function (Blueprint $table) {
            $table->id();
            $table->double('amount');
            $table->foreignIdFor(Teacher::class,'teacher_id')->nullable();
            $table->foreignIdFor(Employee::class,'employee_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create__maturitie_tables');
    }
};
