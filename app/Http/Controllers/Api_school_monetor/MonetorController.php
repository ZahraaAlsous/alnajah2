<?php

namespace App\Http\Controllers\Api_school_monetor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Publish;
use App\Models\Mark;
use App\Models\Classs;
use App\Models\Note_Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Teacher_subject;
use App\Models\Subject;
use App\Models\Out_Of_Work_Employee;
use App\Models\Out_Of_Work_Student;
use Illuminate\Support\Carbon;
use App\Models\Teacher_Schedule;
use App\Models\Program_Student;
use App\Models\Image;

class MonetorController extends Controller
{
    public function student_classification($classification)
    {
        if($classification = 1){
        $stud =Student::where('classification' ,'=', 1)->get();
        $student = User::where($stud->user_id, 'id')->get()->all();
            return response()->json([$stud,$student]);
        }
        else {
            $stud =Student::where('classification' ,'=', 0)->get();
            $student = User::where($stud->user_id, 'id')->get()->all();
                return response()->json([$stud,$student]);
            }
    }

    public function desplay_all_student_regester($year)
{
    $students = User::where('year',$year)->where('user_type', 'student')->with('student.classs')->with('student.section')->get();
$students = $students->map(function ($student) {
    if ($student->image) {
        $student->image_file_url = asset('/upload/' . $student->image);
    } else {
        $student->image_file_url = null; // إذا لم يكن هناك صورة
    }

    return $student;
});
    return response()->json([$students,'all student regester here']);
}
// public function desplay_all_student_regester($year)
// {
//     $academy = Academy::find(1);

//     // جلب الأساتذة مع المعلومات المطلوبة
//     $students = Student::with(['user,class.section'])->whereHas('user', function ($query) use ($academy) {
//         $query->where('year', $academy->year);
//     })->get();

//     $students = $students->map(function ($student) {
//         if ($student->user->image) {
//             $student->user->image_file_url = asset('/upload/' . $student->user->image);
//         } else {
//             $student->user->image_file_url = null; // إذا لم يكن هناك صورة
//         }

//         return $student;
//     });

//     return $students;
// }

    public function desplay_classs_and_section()
    {
        $classs = Classs::get()->all();
        if(!$classs)
        {
            return response()->json(['you havenot any class']);
        }
        $classs1 =  $classs->section;
        return response()->json([$classs,$classs1,'successsssssss']);
    }

    public function show_profile_student($student_id)
    {
        $student = Student::find($student_id);
        if(!$student)
        {
            return response()->json('the student not found ');
        }
        $student->user;
        return response()->json([$student,'sucsseesss ']);

    }
    public function update_profile_student(Request $request,$student_id)
    {
        $student = Student::find($student_id);
        if(!$student)
        {
            return response()->json('the student not found ');
        }
        $validator = Validator::make($request->all(),[
            'calssification' => 'required',
            'school_tuition'=>'required',
            'class_id'=>'required',
            'section_id'=>'required',
            'parentt_id'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $user = $student->user;
        $user_id = $user->id;
        $student->calssification = $request->calssification;
        $student->school_tuition = $request->school_tuition;
        $student->user_id = $user_id;
        $student->class_id =$request->class_id;
        $student->section_id= $request->section_id;
        $student->parentt_id = $request->parentt_id;

        $student->update();
        return response()->json(['sucussssss']);

    }


    public function generateMonthlyAttendanceReport($student_id, $year, $month)
    {
        // استرجاع قائمة الأيام العطل في الشهر
        $holidays = collect([]);

        // حساب عدد الأيام في الشهر
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        // تهيئة مصفوفة لتخزين تفاصيل الحضور لكل يوم في الشهر
        $attendanceDetails = [];

        // تحديث تفاصيل الحضور لكل يوم في الشهر
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $attendanceStatus = 'حاضر';

            if ($date->format('l') !== 'Friday' && $date->format('l') !== 'Saturday') {
                $absence = Out_Of_Work_Student::where('student_id', $student_id)
                    ->whereDate('date', $date)
                    ->first();

                if ($absence) {
                    $attendanceStatus = 'غائب';
                }
            } else {
                $attendanceStatus = 'عطلة';
            }

            $attendanceDetails[] = [
                'date' => $date->toDateString(),
                'attendance_status' => $attendanceStatus,
            ];
        }

        return response()->json([
            'student_id' => $student_id,
            'year' => $year,
            'month' => $month,
            'attendance_details' => $attendanceDetails,
        ]);
    }

    public function desplay_student_marks($student_id)
    {
        $student = Student::find($student_id);
        if(!$student)
        {
            return response()->json(['student not found ']);
        }
        $student->mark;
        return response()->json([$student,'sucssssss']);

    }

    public function desplay_student_note($student_id)
    {
        $student = Student::find($student_id);
        if(!$student)
        {
            return response()->json(['student not found ']);
        }
        $student->note_students;
        return response()->json([$student,'sucssssss']);

    }

    public function create_note_student(Request $request , $student_id)
{
    $student = Student::find($student_id);
    if(!$student)
    {
        return response()->json(['the student not found']);
    }
    $validator = Validator::make($request->all(),[
        'text'=>'required|string',
        'type'=>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $note_student = new Note_Student();

        $note_student->type = $request->type;
        $note_student->text = $request->text;
        $note_student->student_id = $student_id;
        $note_student->user_id = auth()->user()->id;

        $note_student->save();

        return response()->json(['successssss']);

}

    public function all_teatcher()
    {
        $teatcher = Teacher::with('user')->get();
        return $teatcher;
    }
    public function info_teatcher($teatcher_id)
    {
        $teacher = Teacher::where('id', $teatcher_id)->first();
        $teacher_subject = Teacher_subject::where('teacher_id', $teacher->id)->get();

        foreach ($teacher_subject as $subject) {
            $info_subject = Subject::where('id', $subject->subject_id)->get();
            $result[] = $info_subject;
        }
        return [$teacher, $result];
    }

    public function desplay_teacher_course($teacher_id)
    {
        $teacher = Teacher::find($teacher_id);
        if(!$teacher)
        {
            return response()->json(['teacher not found ']);
        }

        return  $teacher->course;

    }


    public function updateWeeklySchedule(Request $request, $teacher_id)
    {
        $teacher = Teacher::find($teacher_id);
        if(!$teacher)
        {
            return response()->json(['the teacher not found']);
        }

        // تحقق من صحة البيانات المرسلة
        $validator = Validator::make($request->all(), [
            // 'teacher_id' => 'required|exists:teachers,id',
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
        ]);

        // حذف برنامج الدوام السابق
        Teacher_Schedule::where('teacher_id', $teacher_id)->delete();

        // إضافة البرنامج الدوامي الجديد
        foreach ($request->schedules as $scheduleData) {
            Teacher_Schedule::create([
                'teacher_id' => $teacher_id,
                'day_of_week' => $scheduleData['day_of_week'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'section_id' => $scheduleData['section_id'],
            ]);
        }

        // إرجاع رسالة ناجحة
        return response()->json(['message' => 'Teacher weekly schedule updated successfully'], 200);
}

public function getTeacherWorkSchedule($teacher_id, $year, $month)
{

    // استرجاع سجل غياب المدرس خلال الشهر المحدد
    $absences = Out_Of_Work_Employee::where('teacher_id', $teacher_id)
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->pluck('date')
        ->toArray();

    // تنسيق البيانات لعرضها بشكل مفهوم
    $workSchedule = [];
    foreach ($absences as $day) {
        $workSchedule[] = [
            'date' => $day,
            'hours' => 0, // عدد الساعات للأيام التي تم فيها الغياب هو صفر
            'sections' => [], // لن يكون هناك شعب لهذا اليوم
        ];
    }

    // حساب عدد الأيام في الشهر
    $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

    // إضافة أيام العمل التي لم يتم فيها الغياب مع عدد ساعات العمل والشعبة
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = Carbon::create($year, $month, $day);
        $dayOfWeek = $date->format('l'); // يوم الأسبوع كاسم النصي، مثل "Sunday"

        if ($date->dayOfWeek != Carbon::FRIDAY && $date->dayOfWeek != Carbon::SATURDAY) {
            $workDetails = $this->getWorkingHoursAndSectionsForDay($teacher_id, $dayOfWeek);

            if ($workDetails['hours'] > 0 && !in_array($date->format('Y-m-d'), $absences)) {
                $workSchedule[] = [
                    'date' => $date->format('Y-m-d'),
                    'hours' => $workDetails['hours'],
                    'sections' => $workDetails['sections'],
                ];
            }
        }
    }

    return response()->json([
        'teacher_id' => $teacher_id,
        'year' => $year,
        'month' => $month,
        'work_schedule' => $workSchedule,
    ]);
}

private function getWorkingHoursAndSectionsForDay($teacher_id, $dayOfWeek)
{
    // استرجاع بيانات الدوام لهذا اليوم مع الشعبة المرتبطة
    $workSchedules = Teacher_Schedule::where('teacher_id', $teacher_id)
        ->where('day_of_week', $dayOfWeek)
        ->with('section')  // تحميل بيانات الشعبة
        ->get();

    if ($workSchedules->isEmpty()) {
        return [
            'hours' => 0,
            'sections' => [],
        ];
    }

    $totalWorkingHours = 0;
    $sections = [];

    foreach ($workSchedules as $workSchedule) {
        if (!empty($workSchedule->start_time) && !empty($workSchedule->end_time)) {
            // حساب عدد ساعات العمل
            $startTime = Carbon::createFromFormat('H:i:s', $workSchedule->start_time);
            $endTime = Carbon::createFromFormat('H:i:s', $workSchedule->end_time);
            $workingHours = $endTime->diffInHours($startTime);
            $totalWorkingHours += $workingHours;

            // إضافة اسم الشعبة أو "N/A" إذا لم تكن موجودة
            $sections[] = $workSchedule->section ? $workSchedule->section->num_section : 'N/A';
        }
    }

    return [
        'hours' => $totalWorkingHours,
        'sections' => $sections,
    ];
}


// دالة لاستخراج عدد ساعات العمل ليوم معين
private function getWorkingHoursForDay($teacher_id, $dayOfWeek)
{
    // استرجاع بيانات الدوام لهذا اليوم
    $workSchedules = Teacher_Schedule::where('teacher_id', $teacher_id)
        ->where('day_of_week', $dayOfWeek)
        ->get();

    if ($workSchedules->isEmpty()) {
        // إذا لم يتم العثور على أي جدول دوام لهذا اليوم
        return 0;
    }

    $totalWorkingHours = 0;

    foreach ($workSchedules as $workSchedule) {
        if (!empty($workSchedule->start_time) && !empty($workSchedule->end_time)) {
            // حساب عدد ساعات العمل بين وقت البداية ووقت النهاية
            $startTime = Carbon::createFromFormat('H:i:s', $workSchedule->start_time);
            $endTime = Carbon::createFromFormat('H:i:s', $workSchedule->end_time);
            $workingHours = $endTime->diffInHours($startTime);
            $totalWorkingHours += $workingHours;
        }
    }

    return $totalWorkingHours;
}


public function calculatemonthlyattendance($teacher_id, $year, $month)
{
    // استرجاع برنامج الدوام الأسبوعي الثابت للمعلم
    $teacherSchedule = Teacher_Schedule::where('teacher_id', $teacher_id)->get();

    // استرجاع قائمة الأيام العطل في الشهر
    $holidays = Out_Of_Work_Employee::where('teacher_id', $teacher_id)
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->pluck('date');

    // حساب عدد الأيام في الشهر
    $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;


    // return response()->json([$daysInMonth]);
    // حساب عدد أيام الدوام وعدد ساعات الدوام لكل يوم في الشهر
    $totalWorkingDays = 0;
    $totalWorkingHours = 0;

    foreach ($teacherSchedule as $schedule) {

        $workingHours = $this->getWorkingHoursForDay($teacher_id, $schedule->day_of_week); // استرجاع عدد ساعات العمل لهذا اليوم

        $workingDaysInMonth = $this->calculateWorkingDaysInMonth($year, $month, $schedule->day_of_week, $holidays, $daysInMonth);
        $totalWorkingDays += $workingDaysInMonth;
        $totalWorkingHours += $workingDaysInMonth * $workingHours;
    }

    return response()->json([
        'teacher_id' => $teacher_id,
        'year' => $year,
        'month' => $month,
        'total_working_days' => $totalWorkingDays,
        'total_working_hours' => $totalWorkingHours,
    ]);
}
    private function calculateWorkingDaysInMonth($year, $month, $dayOfWeek, $holidays, $daysInMonth)
    {
        $totalWorkingDays = 0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            if ($date->format('l') == $dayOfWeek && !$holidays->contains($date->format('Y-m-d')) && !in_array($date->format('l'), ['Friday', 'Saturday'])) {
                $totalWorkingDays++;
            }
        }
        return $totalWorkingDays;
    }



public function getteacherabsences($teacher_id, $year, $month)
{
    // استرجاع الأيام التي تغيب فيها الاستاذ خلال الشهر المحدد
    $absences = Out_Of_Work_Employee::where('teacher_id', $teacher_id)
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->whereNotIn(DB::raw('DAYOFWEEK(date)'), [6, 7]) // استبعاد أيام الجمعة (6) والسبت (7)
        ->get();

    // حساب عدد الأيام التي تم غياب الاستاذ فيها وعدد الساعات التي غاب فيها
    $totalAbsenceDays = $absences->count();
    $totalAbsenceHours = $absences->sum('num_hour_out');

    return response()->json([
        'teacher_id' => $teacher_id,
        'year' => $year,
        'month' => $month,
        'total_absence_days' => $totalAbsenceDays,
        'total_absence_hours' => $totalAbsenceHours,
    ]);
}


public function generateMonthlyAttendanceReportReport($teacher_id, $year, $month)
{
    // استرجاع برنامج الدوام الأسبوعي الثابت للمعلم
    $teacherSchedule = Teacher_Schedule::where('teacher_id', $teacher_id)->get();

    // استرجاع قائمة الأيام العطل في الشهر (يمكن تركها فارغة في حال لم يكن لديك بيانات)
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

            $totalWorkingHours = 0;

            foreach ($schedules as $schedule) {
                // حساب عدد ساعات العمل بين وقت البداية ووقت النهاية
                $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time);
                $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time);
                $workingHours = $endTime->diffInHours($startTime);

                $totalWorkingHours += $workingHours;
            }

            $attendanceDetails[] = [
                'date' => $date->format('l d-m-Y'),  // صيغة التاريخ
                'working_hours' => $totalWorkingHours,
            ];
        } else {
            $attendanceDetails[] = [
                'date' => $date->format('l d-m-Y'),  // صيغة التاريخ
                'working_hours' => 0, // لا يوجد ساعات عمل في أيام العطل أو نهاية الأسبوع
            ];
        }
    }

    // ترتيب الأيام بترتيب تصاعدي
    usort($attendanceDetails, function ($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
    });

    return response()->json([
        'teacher_id' => $teacher_id,
        'year' => $year,
        'month' => $month,
        'attendance_details' => $attendanceDetails,
    ]);
}




    public function info_course($id_course)
    {
        $course = Course::where('id', $id_course)->with('subject')->with('classs')->with('teacher.user')->get();
        return $course;
    }

    public function desplay_all_publish()
    {
        $publish = Publish::with('course')->get()->all();
        return response()->json([$publish,'this is all publish']);
    }
    public function DisplayOrderNewStudent()
    {
        $order = DB::table('orders')->where('student_id','=',null)->where('course_id','=',null)->get();

        return $order;
    }

    // public function add_publish(Request $request)
    // {
    // $validator = Validator::make($request->all(),[
    //     'description'=>'required|string',
    //     'course_id'=>'required',
    //     ]);

    // if ($validator->fails()) {
    //     return response()->json(['errors' => $validator->errors()]);
    // }

    // $publish = new Publish();
    // $publish->description = $request->description;
    // $publish->course_id = $request->course_id;
    // $publish->save();
    // return response()->json(['sucssscceccs']);
    // }

    // public function delete_publish($publish_id)
    // {
    //     $publish = Publish::find($publish_id);
    //     if(!$publish)
    //     {
    //         return response()->json(['the publish not found or was deleted  ']);
    //     }
    //     $publish->delete();
    //     return response()->json(['the publish  deleted  ']);

    // }

    public function add_publish(Request $request)
{
    $validator = Validator::make($request->all(),[
        'description'=>'required|string',
        //'course_id'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $publish = new Publish();
        $publish->description = $request->description;
        //$publish->course_id = $request->course_id ?? null;
        $publish->save();

        if ($request->path) {
            $validator = Validator::make($request->all(),[
                'path' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'Please fix the errors',
                    'errors' => $validator->errors()
                ]);
            }

            $img = $request->path;
            $ext = $img->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $img->move(public_path().'/upload',$imageName);

            $image = new Image;
            $image->path = $imageName;
            $image->description = $request->description;
            $image->publish_id = $publish->id;

            $image->save();

            return response()->json([
                'status' => 'true',
                'message' => 'image upload success',
                'path' => asset('/upload/'.$imageName),
                'data' => $image
            ]);
            return response()->json(['sucssscceccs with img']);
        }

        else {
            return response()->json(['sucssscceccs']);
        }


}

public function delete_publish($publish_id)
{
    $publish = Publish::find($publish_id);
    if(!$publish)
    {
        return response()->json(['the publish not found or was deleted  ']);
    }
    $publish->delete();
    $image = Image::where('publish_id', $publish->id)->delete();
    return response()->json(['the publish  deleted  ']);

}

public function add_mark_to_student(Request $request, $student_id)
    {
    // القيام بالتحقق من وجود الطالب
    $student = Student::find($student_id);
    if(!$student)
    {
        return response()->json(['error' => 'The student not found']);
    }

    // إنشاء وحفظ العلامات
    $mark = new Mark;
    $mark->student_id = $student_id;
    $mark->subject_id = $request->input('subject_id');

    $mark->ponus = $request->input('ponus');
    $mark->homework = $request->input('homework');
    $mark->oral = $request->input('oral');
    $mark->test1 = $request->input('test1');
    $mark->test2 = $request->input('test2');
    $mark->exam_med = $request->input('exam_med');
    $mark->exam_final = $request->input('exam_final');

    // حساب المجموع
    $aggregate = ($mark->ponus ?? 0) + ($mark->homework ?? 0) + ($mark->oral ?? 0) + ($mark->test1 ?? 0) + ($mark->test2 ?? 0) + ($mark->exam_med ?? 0) + ($mark->exam_final ?? 0);

    // تحديد حالة الطالب (ناجح/راسب)
    $mark->state = ($aggregate > 50) ? 1 : 0;

    $mark->save();

    return response()->json(['success' => 'Marks added successfully']);
}


public function editMark(request $request,$student_id, $subject_id)
{
    $student = Student::find($student_id);
    if(!$student)
    {
        return response()->json(['error' => 'The student not found']);
    }

    // التحقق مما إذا كانت العلامة موجودة بالفعل للطالب لنفس المادة
    $mark = Mark::where('student_id', $student_id)
                ->where('subject_id', $subject_id)
                ->first();

    if(!$mark)
    {
        return response()->json(['error' => 'The mark does not exist for this student and subject']);
    }

    // التحقق من أن العلامة التي تم تعديلها تنتمي لنفس الطالب ونفس المادة
    if($mark->student_id != $student_id || $mark->subject_id != $subject_id)
    {
        return response()->json(['error' => 'The mark does not belong to the same student or subject']);
    }

    $mark->ponus = $request->ponus ?? $mark->ponus;
    $mark->homework = $request->homework ?? $mark->homework;
    $mark->oral = $request->oral ?? $mark->oral;
    $mark->test1 = $request->test1 ?? $mark->test1;
    $mark->test2 = $request->test2 ?? $mark->test2;
    $mark->exam_med = $request->exam_med ?? $mark->exam_med;
    $mark->exam_final = $request->exam_final ?? $mark->exam_final;

    $aggregate = ($mark->ponus + $mark->homework + $mark->oral +
        $mark->test1 + $mark->test2 + $mark->exam_med + $mark->exam_final);
    $mark->state = ($aggregate > 50) ? 1 : 0;

    $mark->save();

    return response()->json(['success' => 'Mark updated successfully']);
}

public function addAbsence(Request $request, $student_id)
{
    // التحقق من وجود الطالب
    $student = Student::find($student_id);
    if (!$student) {
        return response()->json(['message' => 'Student not found'], 404);
    }

    // التحقق من صحة البيانات المدخلة
    $validator = Validator::make($request->all(), [
        'date' => 'required|date',
        'justification' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // إنشاء سجل الغياب
    $absence = new Out_Of_Work_Student();
    $absence->date = $request->date;
    $absence->justification = $request->justification;
    $absence->student_id = $student_id;
    $absence->save();

    return response()->json(['message' => 'Absence added successfully'], 200);
}

public function order_on_course($cousre_id)
{
    $course = Course::find($cousre_id);
    if(!$course)
    {
        return response()->json(['the course not opended now']);
    }

    $orders = $course->order;
    return response()->json(['orders' => $orders]);
}


public function upload_program_section(Request $request, $section_id, NotificationController $notificationController)
{

    $validator = Validator::make($request->all(),[
        'path' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt',
        'type' => 'required',
        'description' => 'nullable|string|max:255'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'false',
            'message' => 'Please fix the errors',
            'errors' => $validator->errors()
        ]);
    }
    $program = new Program_Student;
    $program->type = $request->type;
    $program->section_id = $section_id;

    $program->save();

    $img = $request->path;
    $ext = $img->getClientOriginalExtension();
    $imageName = time().'.'.$ext;
    $img->move(public_path().'/upload',$imageName);

    $image = new Image;
    $image->path = $imageName;
    $image->description = $request->description ?? null;
    $image->program_student_id = $program->id;

    if ($image->save()) {
        $title = $program->type;
        $bode = 'تم رفع برنامج جديد';

        $notificationController->sendNotification_for_parent_and_student($title,$body,$section_id);

    }

    return response()->json([
        'status' => 'true',
        'message' => 'image upload success',
        'path' => asset('/upload/'.$imageName),
        'data' => $image
    ]);

}

// //روان ربطت على أساسو
// public function upload_program_section(Request $request, $section_id)
// {
//     $program = new Program_Student;
//     $program->type = $request->type;
//     $program->section_id = $section_id;

//     $program->save();

//     $validator = Validator::make($request->all(),[
//         'path' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt'
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'Please fix the errors',
//             'errors' => $validator->errors()
//         ]);
//     }

//     $img = $request->path;
//     $ext = $img->getClientOriginalExtension();
//     $imageName = time().'.'.$ext;
//     $img->move(public_path().'/upload',$imageName);

//     $image = new Image;
//     $image->path = $imageName;
//     $image->description = $request->description ?? null;
//     $image->program_student_id = $program->id;
//     $image->save();

//     return response()->json([
//         'status' => 'true',
//         'message' => 'image upload success',
//         'path' => asset('/upload/'.$imageName),
//         'data' => $image
//     ]);

// }

//حذف برنامج محدد لشعبة
public function delete_program($id)
{
    $image = Image::where('program_student_id', $id)->first();
    $program = Program_Student::find($id)->delete();

    // حذفت الملف من المجلد يلي خزنتو في
    $imagePath = public_path().'/upload/'.$image->path;
    if(file_exists($imagePath)) {
        unlink($imagePath);
    }

    // حذفت الملف من الداتا عندي
    $image->delete();

    return response()->json([
        'status' => 'true',
        'message' => 'Image deleted successfully'
    ]);
}

//تعديل برنامج لشعبة
public function update_program_section(Request $request, $program_id, NotificationController $notificationController)
{
    $program = Program_Student::find($program_id);
    // $program->type = $request->type ?? $program->type;
    if ($request->has('type') && !empty($request->type)) {
        $program->type = $request->type;
        $program->save();
    }

    $image = Image::where('program_student_id', $program_id)->first();

    if ($request->has('description') && !empty($request->description)) {
        $image->description = $request->description;
        $image->save();
    }

    if ($request->has('path') && !empty($request->path)) {
        $validator = Validator::make($request->all(),[
            'path' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'Please fix the errors',
                'errors' => $validator->errors()
            ]);
        }

        // استرجاع الصورة القديمة بناءً على الـ ID
        // $image = Image::find($id);

        if (!$image) {
            return response()->json([
                'status' => 'false',
                'message' => 'Image not found'
            ]);
        }

        // حذف الصورة القديمة من المجلد إذا كانت موجودة
        $oldImagePath = public_path().'/upload/'.$image->path;
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }

        // رفع الصورة الجديدة
        $img = $request->path;
        $ext = $img->getClientOriginalExtension();
        $imageName = time().'.'.$ext;
        $img->move(public_path().'/upload', $imageName);

        // تحديث مسار الصورة في قاعدة البيانات
        $image->path = $imageName;

        if ($image->save()) {
            $title = $program->type;
            $bode = 'تم تعديل البرنامج';
    
            $notificationController->sendNotification_for_parent_and_student($title,$body,$section_id);
    
        }

        return response()->json([
            'status' => 'true',
            'message' => 'Image updated successfully',
            'path' => asset('/upload/'.$imageName),
            'data' => $image
        ]);
    }



}


}
