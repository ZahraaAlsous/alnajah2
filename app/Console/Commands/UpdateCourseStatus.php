<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use Carbon\Carbon;

class UpdateCourseStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update course status to 0 when the course finish date and time is reached';


    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // الوقت الحالي
        $currentDate = Carbon::now()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        // جلب الدورات التي تحتاج إلى تعديل
        $courses = Course::where('Course_status', 1)
                         ->where('finish_date', '<=', $currentDate)
                         ->where('finish_time', '<=', $currentTime)
                         ->get();

        foreach ($courses as $course) {
            $course->Course_status = 0; // تحديث الحالة إلى 0
            $course->save();            // حفظ التعديلات
        }

        $this->info('Course statuses updated successfully.');
    }
}
