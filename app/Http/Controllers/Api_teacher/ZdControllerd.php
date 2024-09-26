<?php

namespace App\Http\Controllers\Api_teacher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Teacher;
use App\Models\Comment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use App\Notifications\MyNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\Course;
use App\Models\Out_Of_Work_Student;
use App\Models\Teacher_Schedule;
use App\Models\Out_Of_Work_Employee;
use App\Models\File_course;
use App\Models\User;
use App\Models\Note_Student;
use App\Models\Academy;



class ZdControllerd extends Controller
{
    public function upload_file_image_for_course(Request $request, $course_id, NotificationController $notificationController)
{
    $validator = Validator::make($request->all(),[
        'name' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'false',
            'message' => 'Please fix the errors',
            'errors' => $validator->errors()
        ]);
    }

    $img = $request->name;
    $ext = $img->getClientOriginalExtension();
    $imgFileName = time().'.'.$ext;
    $img->move(public_path().'/upload',$imgFileName);

    // if ($ext=="png" || $ext=="jpg" || $ext=="jpeg" || $ext=="gif") {
        $image = new File_course();
    $image->name = $imgFileName;
    $image->description = $request->description;
    $image->course_id = $request->course_id;
    // $image->save();

    if ($image->save()) {
        $course = Course::find($course_id);
        $title = 'ملفات جديدة للدورة '.$course->name_course;
        $body = $image->description;
        
        $notificationController->sendNotification_call($course->teacher->user->fcm_token, $title, $body_s);
        $notificationController->sendNotification_all_student_course($title,$body,$course_id);
    }

    return response()->json([
        'status' => 'true',
        'message' => 'image_file upload success',
        'path' => asset('/upload/'.$imgFileName),
        'data' => $image
    ]);
    // }

    // elseif ($ext=="pdf" || $ext=="docx" || $ext=="txt") {
    //     $file = new File_course();
    // $file->name = $imgFileName;
    // $file->description = $request->description;
    // $file->course_id = $request->course_id;
    // $file->save();

    // return response()->json([
    //     'status' => 'true',
    //     'message' => 'file upload success',
    //     'path' => asset('/upload/'.$imgFileName),
    //     'data' => $file
    // ]);
    // }

}

public function desplay_student_marks($student_id)
    {
        // $student = Student::find($student_id);
        // if(!$student)
        // {
        //     return response()->json(['student not found ']);
        // }
        // $student->mark;
        // return response()->json([$student,'sucssssss']);
        $student = Student::where('id', $student_id)->with('mark.subject')->first();
        return $student;
    }

    //عرض كل غيابات الابن
    public function all_out_work_student($student_id)
    {
        $Out_Of_Work_Student = Out_Of_Work_Student::where('student_id',$student_id)->get();
        return $Out_Of_Work_Student;
    }

    public function generateMonthlyAttendanceReportReport($teacher_id, $year, $month)
    {
        // استرجاع برنامج الدوام الأسبوعي الثابت للمعلم مع ربطه بالشعب
        $teacherSchedule = Teacher_Schedule::with('section')
            ->where('teacher_id', $teacher_id)
            ->get();

        // استرجاع قائمة الأيام العطل في الشهر
        $holidays = Out_Of_Work_Employee::where('teacher_id', $teacher_id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->pluck('date');

        // حساب عدد الأيام في الشهر
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        // تهيئة مصفوفة لتخزين تفاصيل سجل الدوام لكل يوم في الشهر
        $attendanceDetails = [];

        // تحديث تفاصيل سجل الدوام لكل يوم في الشهر
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $dayOfWeek = $date->format('l');

            // تحقق مما إذا كان اليوم هو يوم عمل للمعلم وليس عطلة
            $isHoliday = $holidays->contains($date->format('Y-m-d'));
            $isWeekend = in_array($date->format('l'), ['Friday', 'Saturday']);

            if (!$isHoliday && !$isWeekend) {
                // استرجاع جميع الفترات في اليوم الحالي
                $schedules = $teacherSchedule->where('day_of_week', $dayOfWeek);

                $dailySchedule = [];
                $totalWorkingHours = 0;

                foreach ($schedules as $schedule) {
                    // التحقق من وجود بيانات الشعبة
                    $sectionName = $schedule->section ? $schedule->section->num_section	: 'N/A';

                    // حساب عدد ساعات العمل بين وقت البداية ووقت النهاية
                    $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time);
                    $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time);
                    $workingHours = $endTime->diffInHours($startTime);

                    $totalWorkingHours += $workingHours;

                    // إضافة تفاصيل الشعبة مع ساعات العمل
                    $dailySchedule[] = [
                        'section' => $sectionName,
                        'start_time' => $startTime->format('H:i'),
                        'end_time' => $endTime->format('H:i'),
                        'working_hours' => $workingHours,
                    ];
                }
                return $sectionName;

                $attendanceDetails[] = [
                    'date' => $date->format('l d-m-Y'),  // صيغة التاريخ
                    'working_hours' => $totalWorkingHours,
                    'daily_schedule' => $dailySchedule,
                ];
            } else {
                $attendanceDetails[] = [
                    'date' => $date->format('l d-m-Y'),  // صيغة التاريخ
                    'working_hours' => 0, // لا يوجد ساعات عمل في أيام العطل أو نهاية الأسبوع
                    'daily_schedule' => [],
                ];
            }
        }

        return response()->json([
            'teacher_id' => $teacher_id,
            'year' => $year,
            'month' => $month,
            'attendance_details' => $attendanceDetails,
        ]);
    }
 
    public function display_file_course($course_id)
{
    $files = File_course::where('course_id', $course_id)->get();
    $result = [];  // تأكد من تهيئة المتغير $result كمصفوفة فارغة
    
    foreach ($files as $file) {
        $imageFilePath = str_replace('\\', '/', public_path() . '/upload/' . $file->name);
        
        if (file_exists($imageFilePath)) {
            $file->image_file_url = asset('/upload/' . $file->name);
            $result[] = $file;  // أضف الملف مباشرة إلى المصفوفة $result
        }
    }
    
    if (!empty($result)) {
        return $result;
    } else {
        return response()->json([
            'status' => 'false',
            'message' => 'No images_files found'
        ]);
    }
}

   //البحث عن طالب ضمن طلاب شعبة محددة
   public function search_student_in_section(Request $request, $section_id)
   {
       // تقسيم مدخل البحث إلى أجزاء بناءً على المسافة
       $keywords = explode(' ', $request->q);
   
       // إعداد استعلام أساسي
       $query = User::where('user_type', 'student')
                    ->where('status', '1')->whereHas('student', function ($query1) use ($section_id) {
                       $query1->whereHas('section', function($query2) use ($section_id) {
                           $query2->where('id', $section_id);
                       });
                    });
   
       // إضافة شروط البحث لكل كلمة في الكلمات المفتاحية
       foreach ($keywords as $keyword) {
           $query->where(function ($subQuery) use ($keyword) {
               $subQuery->where('first_name', 'LIKE', "%{$keyword}%")
                        ->orWhere('last_name', 'LIKE', "%{$keyword}%");
           });
       }
   
       // تنفيذ الاستعلام
       $student = $query->with('student')->with('student.classs')->with('student.section')->get();
   
       return response()->json($student);
   }

   //عرض الملاحظات التي بحق الابن
   public function display_note($student_id)
   {
        $note= Note_Student::where('student_id', $student_id)->with('user')->get();
        return $note;
   }

   public function display_info_academy()
    {
        $info = Academy::find('1');
        
        return $info;
    }
}

