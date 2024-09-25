<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use App\Models\Salary;
use App\Models\Academy;

class StoreEmployeeSalaries extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salaries:store-employees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store employee salaries at the end of each month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        
        // Get all employees
        $employees = Employee::all();
        $academy = Academy::find(1);

        
        foreach ($employees as $employee) {

            $advanceAmount = $employee->maturitie()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('amount');

            $salary_after_advanceAmount = $employee->salary - $advanceAmount;

            // Store the salary in the database
            Salary::create([
                'salary_of_teacher' => $salary_after_advanceAmount, // استخدام الراتب من جدول الموظفين
                'month' => Carbon::now()->startOfMonth(), // تسجيل الشهر الحالي
                'year'=>$academy->year,
                'teacher_id' => null, // تعيين teacher_id إلى null
                'employee_id' => $employee->id, // استخدام employee_id
                'status' => 0, // لم يستلم المعاش بعد
            ]);
        }

        $this->info('Employee salaries stored successfully.');
    }
}
