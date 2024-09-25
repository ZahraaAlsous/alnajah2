<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\CalculateTeacherSalary;
use App\Models\Teacher;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
            // جدولة تخزين رواتب الموظفين في آخر يوم من كل شهر
    $schedule->command('salaries:store-employees')->everyMinute();

     // تشغيل الأمر في اليوم الأول من كل شهر في منتصف الليل
     $schedule->command('salaries:store-teachers-salaries')->everyMinute();

     // جدولة الأمر لتشغيله كل دقيقة
    $schedule->command('courses:update-status')->everyMinute();

    // قم بتشغيل الوظيفة لكل معلم شهريًا
    // $teachers = Teacher::all();
    // foreach ($teachers as $teacher) {
    //     $schedule->job(new CalculateTeacherSalary($teacher->id, now()->year, now()->month))
    //         ->monthlyOn(1, '20:59'); // تشغيل في اليوم الأول من كل شهر في منتصف الليل
    // }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
