<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Models\Teacher_Schedule;
use App\Models\Teacher;
use App\Models\Out_Of_Work_Employee;
use App\Models\Salary;


class CalculateTeacherSalary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $teacher_id;
    protected $year;
    protected $month;

    /**
     * Create a new job instance.
     */
    public function __construct($teacher_id, $year, $month)
    {
        $this->teacher_id = $teacher_id;
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // استرجاع برنامج الدوام الأسبوعي للأستاذ
        $teacherSchedule = Teacher_Schedule::where('teacher_id', $this->teacher_id)->get();

        // استرجاع قائمة أيام العطل والغيابات في الشهر
        $holidays = Out_Of_Work_Employee::where('teacher_id', $this->teacher_id)
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->pluck('date')->toArray();

        // حساب عدد الأيام في الشهر
        $daysInMonth = Carbon::createFromDate($this->year, $this->month, 1)->daysInMonth;

        // استرجاع أجر الساعة للأستاذ
        $teacher = Teacher::findOrFail($this->teacher_id);
        $hourlyRate = $teacher->cost_hour;
        $addedHour = $teacher->num_hour_added;

        $totalWorkingHours = 0;

        // حساب عدد ساعات العمل في الشهر
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($this->year, $this->month, $day);
            $dayOfWeek = $date->format('l');

            if (in_array($date->toDateString(), $holidays) || in_array($dayOfWeek, ['Friday', 'Saturday'])) {
                continue; // تخطي أيام العطل والغيابات
            }

            foreach ($teacherSchedule as $schedule) {
                if ($schedule->day_of_week == $dayOfWeek) {
                    $workingHours = $this->getWorkingHoursForDays($schedule);
                    $totalWorkingHours += $workingHours;
                }
            }
        }

        // حساب الراتب الشهري
        $monthlySalary = ($totalWorkingHours + $addedHour) * $hourlyRate;

        // تخزين الراتب الشهري في جدول salary
        Salary::create([
            'salary_of_teacher' => $monthlySalary,
            'month' => Carbon::createFromDate($this->year, $this->month, 1),
            'teacher_id' => $this->teacher_id,
            'employee_id' => null, // يجعل قيمة employee_id null
            'status' => 0,
        ]);
    }

    private function getWorkingHoursForDays($schedule)
    {
        // هذا تابع مساعد لحساب عدد ساعات العمل بناءً على الجدول
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        return $endTime->diffInHours($startTime);
    }
}
