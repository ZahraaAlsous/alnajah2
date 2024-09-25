<?php

namespace App\Http\Controllers\Api_student;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Classs;
use App\Models\Subject;
use App\Models\Archive;
use App\Models\Homework;
use App\Models\Program_Student;
use App\Models\Note_Student;
use App\Models\Publish;
use Illuminate\Support\Facades\Auth;
use App\Models\Image;
use App\Models\Image_Archive;
use App\Models\File_Archive;
use App\Models\Accessories;
use App\Models\Academy;
use App\Models\Course;
use App\Models\Expenses;
use App\Models\File_course;
use App\Models\Mark;

class Student_operationController extends BaseController
{
    //عرض مواد الطالب
    public function display_subject(Request $request)
    {

        $student = Student::where('user_id',$request->user()->id)->first();
        //$student = Student::where('user_id', $user_id)->first();
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $subject = Subject::where('class_id', $student->class_id)->get();

        return $subject;

    }

    //عرض صور مواد الطالب
    public function display_img_subject($subject_id)
    {
        $user= User::where('id',auth()->user()->id)->first();
        if (!$user) {
            return response()->json(['error' => 'user not found'], 404);
        }

        $archive = Archive::where('year',$user->year)->where('subject_id', $subject_id)->first();
        //صور السنة الحالية للمادة المحددة
        $image_select_year = Image_Archive::where('archive_id',$archive->id)->get();
        foreach ($image_select_year as $i) {
            $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->name);
                        //return response()->file($imagePath);
                        if (file_exists($imagePath)) {
                            $i->image_url = asset('/upload/' . $i->name);
                            $result[] = [
                                // 'path' => $imagePath,
                                'image_info' => $i
                            ];
                        }
        }

        // //صور السنة الحالية للمادة المحددة
        // $file_select_year = File_Archive::where('archive_id',$archive->id)->get();
        // foreach ($file_select_year as $f) {
        //     $filePath = str_replace('\\', '/', public_path().'/upload/'.$f->name);
        //                 //return response()->file($imagePath);
        //                 if (file_exists($filePath)) {
        //                     $f->file_url = asset('/upload/' . $f->name);
        //                     $result[] = [
        //                         // 'path' => $filePath,
        //                         'file_info' => $f
        //                     ];
        //                 }
        // }
        //عم نشوف إذا في نتائج أو لاء
        if (!empty($result)) {
            // return response()->json([
            //     'status' => 'true',
            //     'images' => $result
            // ]);

            return $result;
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'No images found'
            ]);
        }
    }

    //عرض ملفات مواد الطالب
    public function display_file_subject($subject_id)
    {
        $user= User::where('id',auth()->user()->id)->first();
        if (!$user) {
            return response()->json(['error' => 'user not found'], 404);
        }

        $archive = Archive::where('year',$user->year)->where('subject_id', $subject_id)->first();
        // //صور السنة الحالية للمادة المحددة
        // $image_select_year = Image_Archive::where('archive_id',$archive->id)->get();
        // foreach ($image_select_year as $i) {
        //     $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->name);
        //                 //return response()->file($imagePath);
        //                 if (file_exists($imagePath)) {
        //                     $i->image_url = asset('/upload/' . $i->name);
        //                     $result[] = [
        //                         // 'path' => $imagePath,
        //                         'image_info' => $i
        //                     ];
        //                 }
        // }

        //صور السنة الحالية للمادة المحددة
        $file_select_year = File_Archive::where('archive_id',$archive->id)->get();
        foreach ($file_select_year as $f) {
            $filePath = str_replace('\\', '/', public_path().'/upload/'.$f->name);
                        //return response()->file($imagePath);
                        if (file_exists($filePath)) {
                            $f->file_url = asset('/upload/' . $f->name);
                            $result[] = [
                                // 'path' => $filePath,
                                'file_info' => $f
                            ];
                        }
        }
        //عم نشوف إذا في نتائج أو لاء
        if (!empty($result)) {
            // return response()->json([
            //     'status' => 'true',
            //     'images' => $result
            // ]);

            return $result;
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'No file found'
            ]);
        }
    }

    public function orderCourse($course_id, NotificationController $notificationController)
    {
        $user = User::where('id',auth()->user()->id)->first();
        $student = Student::where('user_id', auth()->user()->id)->first();
        $new = new Order;

        $new->first_name = $user->first_name;
        $new->last_name = $user->last_name;
        $new->father_name = $user->father_name;
        //$new->mother_name = $user->mother_name;
        $new->birthday = $user->birthday;
        $new->gender = $user->gender;
        $new->phone = $user->phone;
        $new->address = $user->address;
        $new->email = $user->email;
        // $new->classification = $user->classification;
        $new->classification ='0';
        //$new->class = $request->class;
        //$new->year = $request->year;
        $new->student_type = '10';
        $new->student_id = $student->id;
        $new->course_id = $course_id;
        // $f = Order::where('student_id',$student->id)->where('course_id',$course_id)->first();
        // return $f;
        if (Order::where('student_id',$student->id)->where('course_id',$course_id)->count() != 0) {
            return 'you can not do that you have order for this course';
        }
        // $new->save();

        if ($new->save()) {
            $course = Course::find($course_id);
            $title = 'طلب تسجيل دورة';
            $body_m = 'يريد الطالب '.$user->firs_name.' '.$user->last_name.' التسجيل في دورة '.$course->name_course;
            $body_p = 'قد أرسل الطالب '.$user->firs_name.' '.$user->last_name.' التسجيل في دورة '.$course->name_course;

            $notificationController->sendNotification_for_parent($title,$body_p,$student->id);
            $notificationController->sendNotification_for_all_monetor($title,$body_m);
            $notificationController->sendNotification_for_all_admin($title,$body_m);

        }

        //كلشي تحت لتغير حالة الدورة من قيد الدراسة إلى مفتوحة
        //عدد الطلاب المسجلين في الدورة
        // $num_order_for_course = Order::where('course_id',$course_id)->where('student_type','11')->count();

        // $course = Course::find($course_id);

        // //المبلغ الذي جمعه المعهد من الطلاب المسجلين
        // $Money = $num_order_for_course * $course->cost_course;

        // // المبلغ الذي جمعه المعهد بعد إعطاء المدرس نسبته
        // $Money_without_teacher = $Money * ($course->percent_teacher) / 100;

        // //مصاريف الدورة الكلية
        // $expenses = Expenses::where('course_id',$course_id)->sum('total_cost') ?? 0;

        // //مربح المعهد من الدورة
        // $Money_win =  $Money_without_teacher - $expenses ;

        // if ($Money_win >= $course->Minimum_win) {
        //     $course->Course_status = 1;
        //     $course->save();
        // }

        // return $Money_win;
        return $this->responseData("success",$new);
    }

    //عرض الدورات التي سجل فيها الطالب
    public function my_course()
    {
        $student = Student::where('user_id', auth()->user()->id)->first();
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        $order = Order::where('student_id', $student->id)->with('course.subject')->with('course.teacher.user')->get();

        return $order;
    }

    //عرض وظائف الطالب لمادة محددةzahraa
    // public function homework_subject($subject_id)
    // {
    //     $user= User::where('id',auth()->user()->id)->first();
    //     if (!$user) {
    //         return response()->json(['error' => 'user not found'], 404);
    //     }

    //     $homework = Homework::where('year',$user->year)->where('subject_id', $subject_id)->get();
    //     foreach ($homework as $h) {
    //         $accessori = Accessories::where('home_work_id',$h->id)->get();
    //         foreach ($accessori as $a) {
    //             $homework_path = str_replace('\\', '/', public_path().'/upload/'.$a->path);
    //                     //return response()->file($imagePath);
    //                     if (file_exists($homework_path)) {
    //                         $result[] = [
    //                             'homework_info' => $h,
    //                             'path' => $homework_path,
    //                             'file_image_info' => $a

    //                         ];
    //                     }
    //         }
    //     }
    //     //عم نشوف إذا في نتائج أو لاء
    //     if (!empty($result)) {
    //         // return response()->json([
    //         //     'status' => 'true',
    //         //     'images' => $result
    //         // ]);
    //         return $result;
    //     } else {
    //         return response()->json([
    //             'status' => 'false',
    //             'message' => 'No images found'
    //         ]);
    //     }


    //     // return $homework;
    // }
//     public function homework_subject($subject_id)
// {
//     $user= User::where('id',auth()->user()->id)->first();
//     if (!$user) {
//         return response()->json(['error' => 'user not found'], 404);
//     }

//     $homework = Homework::where('year',$user->year)->where('subject_id', $subject_id)->get();
//     $result = [];
//     foreach ($homework as $h) {
//         $accessori = Accessories::where('home_work_id',$h->id)->get();
//         $homework_info = [
//             'homework_info' => $h,
//             'file_image_info' => []
//         ];
//         foreach ($accessori as $a) {
//             $homework_path = str_replace('\\', '/', public_path().'/upload/'.$a->path);
//             if (file_exists($homework_path)) {
//                 $homework_info['file_image_info'][] = [
//                     'path' => $homework_path,
//                     'file_image_info' => $a
//                 ];
//             }
//         }
//         if (!empty($homework_info['file_image_info'])) {
//             $result[] = $homework_info;
//         }
//     }

//     if (!empty($result)) {
//         return $result;
//     } else {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'No images found'
//         ]);
//     }
// }

//good
// public function homework_subject($subject_id)
// {
//     $user = User::where('id', auth()->user()->id)->first();

//     if (!$user) {
//         return response()->json(['error' => 'user not found'], 404);
//     }

//     $homework = Homework::where('year', $user->year)->where('subject_id', $subject_id)->get();
//     $result = [];

//     foreach ($homework as $h) {
//         $accessories = Accessories::where('home_work_id', $h->id)->get();
//         $homework_info = [
//             'homework_info' => $h,
//             'file_image_info' => []
//         ];

//         foreach ($accessories as $a) {
//             $homework_path = str_replace('\\', '/', public_path() . '/upload/' . $a->path);

//             if (file_exists($homework_path)) {
//                 $homework_info['file_image_info'][] = [
//                     'path' => $homework_path,
//                     'file_image_info' => $a
//                 ];
//             }
//         }

//         $result[] = $homework_info;
//     }

//     if (!empty($result)) {
//         return $result;
//     } else {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'No images found'
//         ]);
//     }
// }


// public function homework_subject($subject_id)
// {
//     $user = User::where('id', auth()->user()->id)->first();

//     if (!$user) {
//         return response()->json(['error' => 'user not found'], 404);
//     }
//     $homework = Homework::where('year', $user->year)->where('subject_id', $subject_id)->with('subject')->get();
//     // $homework = Homework::where('year', $user->year)->where('subject_id', $subject_id)->with('subject')->get();
//     return $homework;
// }
// public function homework_subject($subject_id)
// {
//     $user = auth()->user();

//     if (!$user) {
//         return response()->json(['error' => 'user not found'], 404);
//     }

//     $homeworks = Homework::where('year', $user->year)
//         ->where('subject_id', $subject_id)
//         ->with('accessories')  // استخدام العلاقة لجلب الملحقات
//         ->get();

//     $result = [];

//     foreach ($homeworks as $homework) {
//         $homework_info = $homework->toArray();  // تحويل homework إلى مصفوفة
//         $homework_info['file_image_info'] = [];

//         foreach ($homework->accessories as $accessory) {
//             $accessory_array = $accessory->toArray(); // تحويل accessory إلى مصفوفة
//             $accessory_array['image_url'] = asset('upload/' . $accessory->path);
//             $homework_info['file_image_info'][] = $accessory_array;
//         }

//         $result[] = $homework_info;
//     }

//     if (!empty($result)) {
//         return response()->json([
//             'status' => 'true',
//             'homeworks' => $result
//         ]);
//     } else {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'No images found'
//         ]);
//     }
// }


// public function homework_subject($subject_id)
// {
//     $user = User::where('id', auth()->user()->id)->first();

//     if (!$user) {
//         return response()->json(['error' => 'user not found'], 404);
//     }

//     $homework = Homework::where('year', $user->year)->where('subject_id', $subject_id)->get();
//     $result = [];

//     foreach ($homework as $h) {
//         $accessories = Accessories::where('home_work_id', $h->id)->get();
//         $homework_info = [
//             'homework_info' => $h,
//             'file_image_info' => []
//         ];

//         foreach ($accessories as $a) {
//             $homework_path = str_replace('\\', '/', public_path() . '/upload/' . $a->path);
//             // $h->$a->path;
//             $a->image_file_url = asset('/upload/' . $a->path);
//             if (file_exists($homework_path)) {
//                 $homework_info['file_image_info'][] = [
//                     // 'path' => $homework_path,
//                     'file_image_info' => $a
//                 ];
//             }
//         }

//         $result[] = $homework_info;
//     }

//     if (!empty($result)) {
//         return $result;
//     } else {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'No images found'
//         ]);
//     }
// }

public function homework_subject($subject_id)
{
    $user = User::where('id', auth()->user()->id)->first();

    if (!$user) {
        return response()->json(['error' => 'user not found'], 404);
    }

    $homework = Homework::where('year', $user->year)->where('subject_id', $subject_id)->with('subject')->with('accessories')->get();

    $result = [];

    foreach ($homework as $h) {
        $homework_info = $h->toArray();
        $homework_info['accessories'] = [];

        foreach ($h->accessories as $a) {
            $a->image_file_url = asset('/upload/' . $a->path);
            $homework_info['accessories'][] = $a;
        }

        $result[] = ['homework_info' => $homework_info];
    }

    if (!empty($result)) {
        // return response()->json(['status' => 'success', 'data' => $result]);

        return $result;
    } else {
        return response()->json(['status' => 'false', 'message' => 'No images found']);
    }
}












//عرض ملحقات وظيفة محددة
public function file_image_homework($homework_id)
    {
        $image_select_year = Accessories::where('home_work_id',$homework_id)->get();
        foreach ($image_select_year as $i) {
            $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->name);
                        //return response()->file($imagePath);
                        if (file_exists($imagePath)) {
                            $i->image_file_url = asset('/upload/' . $i->name);
                            $result[] = [
                                // 'path' => $imagePath,
                                'image_file_info' => $i
                            ];
                        }
        }
        //عم نشوف إذا في نتائج أو لاء
        if (!empty($result)) {
            return response()->json([
                'status' => 'true',
                'images' => $result
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'No images found'
            ]);
        }
    }


    // public function Read_File($accessori_id)
    // {
    //     $accessori = Accessories::where('id',$accessori_id)->get();
    //     return Response::download($filepath,$accessori->name.".".$file->extension);
    // }

    //عرض برنامج الدوام الخاص بالطالب
// public function programe_week()
// {
//     $student = Student::where('user_id', auth()->user()->id)->first();
//     $section_id = $student->section_id;
//     //$programe = Program_Student::where('section_id', $student->section_id)->get();
//     $programe = Program_Student::all();

//     if ($programe) {
//         $result = [];

//         foreach ($programe as $p) {
//             if ($p->section_id == $student->section_id) {
//                 $img = Image::all();
//                 foreach ($img as $i) {
//                     if ($p->id == $i->program_student_id) {
//                         $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->path);
//                         if (file_exists($imagePath)) {
//                             $i->image_file_url = asset('/upload/' . $i->path);
//                             $result[] = [
//                                 // 'path' => $imagePath,
//                                 'image_info' => $i,
//                                 'program' => $p
//                             ];
//                         }
//                     }
//                 }
//             }
//         }

//         if (!empty($result)) {
//             return response()->json([
//                 // 'status' => 'true',
//                 // 'images' => $result
//                 $result,
//             ]);
//         } else {
//             return response()->json([
//                 'status' => 'false',
//                 'message' => 'No images found'
//             ]);
//         }
//     } else {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'Program not found for this student'
//         ]);
//     }
// }

// public function programe_week()
// {
//     $student = Student::where('user_id', auth()->user()->id)->first();

//     if (!$student) {
//         return response()->json(['status' => 'false', 'message' => 'Student not found'], 404);
//     }

//     $section_id = $student->section_id;
//     $programs = Program_Student::where('section_id', $section_id)->get();

//     if ($programs->isEmpty()) {
//         return response()->json(['status' => 'false', 'message' => 'Program not found for this student'], 404);
//     }

//     $result = [];

//     foreach ($programs as $program) {
//         $images = Image::where('program_student_id', $program->id)->get();

//         foreach ($images as $image) {
//             $imagePath = public_path('/upload/' . $image->path);

//             if (file_exists($imagePath)) {
//                 $image->image_file_url = asset('/upload/' . $image->path);
//                 $result[] = [
//                     'program' => $program,
//                     'image_info' => $image
//                 ];
//             }
//         }
//     }

//     if (!empty($result)) {
//         return response()->json($result);
//     } else {
//         return response()->json(['status' => 'false', 'message' => 'No images found'], 404);
//     }
// }


public function programe_week()
{
    $student = Student::where('user_id', auth()->user()->id)->first();

    if (!$student) {
        return response()->json(['status' => 'false', 'message' => 'Student not found'], 404);
    }

    $section_id = $student->section_id;
    $programs = Program_Student::where('section_id', $section_id)->get();

    if ($programs->isEmpty()) {
        return response()->json(['status' => 'false', 'message' => 'Program not found for this student'], 404);
    }

    $result = [];

    foreach ($programs as $program) {
        $images = Image::where('program_student_id', $program->id)->get();

        foreach ($images as $image) {
            $imagePath = public_path('/upload/' . $image->path);

            if (file_exists($imagePath)) {
                $program->image_file_url = asset('/upload/' . $image->path);
                $result[] = [
                    'program' => $program,
                    // 'image_info' => $image
                ];
            }
        }
    }

    if (!empty($result)) {
        // return response()->json($result);

        // return $result;
        return $programs;
    } else {
        return response()->json(['status' => 'false', 'message' => 'No images found'], 404);
    }
}

    //عرض السنوات التي تحتوي ملفات للأرشيف حسب المادة
    public function display_year_archive($subject_id)
    {
        $archive = Archive::where('subject_id',$subject_id)->get();
        return $archive;
    }

    //عرض ملفات مادة محددة حسب سنة محددة
    public function file_subject_year($subject_id,$year)
    {
        $archive = Archive::where('subject_id',$subject_id)->where('year', $year)->first();
        // //صور السنة الحالية للمادة المحددة
        // $image_select_year = Image_Archive::where('archive_id',$archive->id)->get();
        // foreach ($image_select_year as $i) {
        //     $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->name);
        //                 //return response()->file($imagePath);
        //                 if (file_exists($imagePath)) {
        //                     $i->image_url = asset('/upload/' . $i->name);
        //                     $result[] = [
        //                         // 'path' => $imagePath,
        //                         'image_info' => $i
        //                     ];
        //                 }
        // }

        //ملفات السنة الحالية للمادة المحددة
        $file_select_year = File_Archive::where('archive_id',$archive->id)->get();
        foreach ($file_select_year as $f) {
            $filePath = str_replace('\\', '/', public_path().'/upload/'.$f->name);
                        //return response()->file($imagePath);
                        if (file_exists($filePath)) {
                            $f->file_url = asset('/upload/' . $f->name);
                            $result[] = [
                                // 'path' => $filePath,
                                'file_info' => $f
                            ];
                        }
        }
        //عم نشوف إذا في نتائج أو لاء
        if (!empty($result)) {
            // return response()->json([
            //     'status' => 'true',
            //     'files' => $result
            // ]);
            return $result;
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'No images found'
            ]);
        }
    }

    //عرض صور مادة محددة حسب سنة محددة
    public function img_subject_year($subject_id,$year)
    {
        $archive = Archive::where('subject_id',$subject_id)->where('year', $year)->first();
        //صور السنة الحالية للمادة المحددة
        $image_select_year = Image_Archive::where('archive_id',$archive->id)->get();
        foreach ($image_select_year as $i) {
            $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->name);
                        //return response()->file($imagePath);
                        if (file_exists($imagePath)) {
                            $i->image_url = asset('/upload/' . $i->name);
                            $result[] = [
                                // 'path' => $imagePath,
                                'image_info' => $i
                            ];
                        }
        }

        //عم نشوف إذا في نتائج أو لاء
        if (!empty($result)) {
            // return response()->json([
            //     'status' => 'true',
            //     'files' => $result
            // ]);
            return $result;
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'No images found'
            ]);
        }

    }

    //عرض الملاحظات التي بحق الطالب
    public function display_note()
    {
        $student = Student::where('user_id', auth()->user()->id)->first();

        $note= Note_Student::where('student_id', $student->id)->with('user')->get();

        return $note;
    }

//     public function publish()
//     {
// $publish = Publish::all();
// $result = [];

// foreach ($publish as $p) {
//     $images = Image::where('publish_id', $p->id)->get();
//     $imageData = [];

//     foreach ($images as $i) {
//         $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->path);

//         if (file_exists($imagePath)) {
//             $i->image_url = asset('/upload/' . $i->path);
//             $imageData[] = [
//                 'file_info' => $i
//             ];
//         }
//     }

//     $result[] = [
//         'ad_info' => $p,
//         'images' => $imageData
//     ];
// }

// if (!empty($result)) {
//     return response()->json([
//         'status' => 'true',
//         'ads' => $result
//     ]);
// } else {
//     return response()->json([
//         'status' => 'false',
//         'message' => 'No images found'
//     ]);
// }
//     }
public function publish()
{
    $publish = Publish::orderBy('created_at', 'desc')->get();
    $result = [];

    foreach ($publish as $p) {
        $images = Image::where('publish_id', $p->id)->get();
        $imageData = [];

        foreach ($images as $i) {
            $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->path);

            if (file_exists($imagePath)) {
                $i->image_url = asset('/upload/' . $i->path);
                $imageData[] = [
                    'file_info' => $i
                ];
            }
        }

        $result[] = [
            'ad_info' => $p,
            'images' => $imageData
        ];
    }

    if (!empty($result)) {
        return response()->json([
            'status' => 'true',
            'ads' => $result
        ]);
    } else {
        return response()->json([
            'status' => 'false',
            'message' => 'No images found'
        ]);
    }
}



    // public function show_my_profile()
    // {
    //     $user = User::where('id',auth()->user()->id)->with('student')->first();
    //     if ($user->image != null) {
    //         $i = public_path().'/upload/'.$user->image;

    // if (file_exists($i)) {
    //     return response()->file($i);
    // } else {
    //     return response()->json([
    //         'status' => 'false',
    //         'message' => 'Image not found'
    //     ]);
    // }
    //     }
    //     return $user;
    // }

    public function show_my_profile()
{
    $user = User::where('id', auth()->user()->id)->with('student.section.classs')->first();

    if ($user && $user->image != null) {
        $imagePath = str_replace('\\', '/', public_path().'/upload/'.$user->image);
        // public_path() . '/upload/' . $user->image;
        if (file_exists($imagePath)) {
            // إضافة رابط الصورة إلى الكائن
            $user->image_url = asset('/upload/' . $user->image);
        } else {
            // إذا كانت الصورة غير موجودة في المجلد
            $user->image_url = null;
        }
    } else {
        // إذا لم يكن هناك صورة للمستخدم
        $user->image_url = null;
    }

    return response()->json([
        'status' => 'true',
        'user' => $user
    ]);
}

//عرض معلومات المعهد
public function display_info_academy()
{
    $info = Academy::all();
    return $info;
}

public function edit_some_info_profile(Request $request)
{
    $validator = Validator::make($request->all(), [
        'address' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    if ($validator->fails()) {
        return $this->responseError(['errors' => $validator->errors()]);
    }

    $user = User::where('id', auth()->user()->id)->with('student')->first();

    if ($request->has('phone') && !empty($request->phone)) {
        $phone = $request->phone;
        if (!preg_match('/^(\+?963|0)?9\d{8}$/', $phone)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Syrian phone number'], 400);
        }
        $user->phone = $request->phone;
    }

    if ($request->has('address') && !empty($request->address)) {
        $user->address = $request->address;
    }

    if ($request->has('password') && !empty($request->password)) {
        if (!$request->has('conf_password') || $request->password !== $request->conf_password) {
            return response()->json(['status' => 'error', 'message' => 'Passwords do not match'], 400);
        }
        $user->password = Hash::make($request->password);
        $user->conf_password = Hash::make($request->conf_password);
    }

    if ($request->hasFile('image')) {
        if ($user->image != null) {
            $oldImagePath = public_path().'/upload/'.$user->image;
    if (file_exists($oldImagePath)) {
        unlink($oldImagePath);
    }

    // رفع الصورة الجديدة
    $img = $request->image;
    $ext = $img->getClientOriginalExtension();
    $imageName = time().'.'.$ext;
    $img->move(public_path().'/upload', $imageName);

    // تحديث مسار الصورة في قاعدة البيانات
    $user->image = $imageName;
        }

        else {
            $img = $request->image;
    $ext = $img->getClientOriginalExtension();
    $imageName = time().'.'.$ext;
    $img->move(public_path().'/upload',$imageName);

    $user->image = $imageName;
        }
    }

    $user->save();

    return response()->json(['status' => 'success', 'message' => 'Profile updated successfully', 'user' => $user]);
}

// public function display_file_course($course_id)
// {

//     $file = File_course::where('course_id',$course_id)->get();
//     // return $file;
//     // $result[] = [];
//     foreach ($file as $f) {
//         $image_file_Path = str_replace('\\', '/', public_path().'/upload/'.$f->name);
//                     //return response()->file($imagePath);
//                     if (file_exists($image_file_Path)) {
//                         $f->image_file_url = asset('/upload/' . $f->name);
//                         $result[] = [
//                             // 'path' => $imagePath,
//                             // 'image_info' => $f
//                             $f
//                         ];
//                     }
//     }

//     //عم نشوف إذا في نتائج أو لاء
//     if (!empty($result)) {
//         return $result;
//     } else {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'No images found'
//         ]);
//     }
   
// }
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

public function getStudentOverallResult()
    {
        $user = User::find(auth()->user()->id);
        
        // التحقق من وجود الطالب
        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // جلب جميع العلامات الخاصة بالطالب
        $marks = Mark::where('student_id', $student->id)->get();

        if ($marks->isEmpty()) {
            return response()->json(['error' => 'No marks found for the student'], 404);
        }

        // التحقق من حالة النجاح أو الرسوب
        $isPassed = true;
        $subjectDetails = [];

        foreach ($marks as $mark) {
            $subject = Subject::find($mark->subject_id);

            // حساب المجموع بدون الـ ponus
            $aggregate = ($mark->homework ?? 0) + ($mark->oral ?? 0) +
                        ($mark->test1 ?? 0) + ($mark->test2 ?? 0) + ($mark->exam_med ?? 0) +
                        ($mark->exam_final ?? 0);

            // التحقق من إضافة الـ ponus
            if ($mark->ponus !== null && $mark->ponus > 0) {
                $remainingToHundred = 100 - $aggregate;

                // إذا كانت المحصلة الحالية أقل من 100، أضف الجزء المناسب من الـ ponus
                if ($remainingToHundred > 0) {
                    $aggregate += min($mark->ponus, $remainingToHundred);
                }
            }

            // تحديد حالة المادة
            $isSubjectPassed = $aggregate > 50 && $mark->exam_final != 0;

            // إضافة تفاصيل المادة
            $subjectDetails[] = [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'total_marks' => $aggregate,
                'is_passed' => $isSubjectPassed ? 'Passed' : 'Failed',
            ];

            // إذا كان الطالب راسبًا في مادة واحدة على الأقل، يكون راسبًا بشكل عام
            if (!$isSubjectPassed) {
                $isPassed = false;
            }
        }

        return response()->json([
            'student_id' => $student->id,
            'overall_result' => $isPassed ? 'Passed' : 'Failed',
            'subjects' => $subjectDetails,
        ]);
    }



}
