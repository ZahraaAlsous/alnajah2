<?php

namespace App\Http\Controllers\Api_parentt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Parentt;
use App\Models\Program_Student;
use App\Models\Subject;
use App\Models\Homework;
use App\Models\Note_Student;
use App\Models\Mark;
use App\Models\Image;
use App\Models\Accessories;
use App\Models\Order;

class ParenttController extends Controller
{
    //عرض جميع أبنائي المسجلين بالمعهد
    // public function displayAllBaby(Request $request)
    // {
    // $parent = Parentt::where('id', auth()->user()->id)->with('student.user')->get();
    // return $parent;
    // }

    //رابطة سرى عليه
    // public function displayAllBaby(Request $request)
    // {
    //  $parent = Parentt::where('id', auth()->user()->id)
    //     // ->whereHas('student.user', function ($query) {
    //     //     $query->where('status', '1');
    //     // })
    //     ->with(['student' => function ($query) {
    //         $query->whereHas('user', function ($query) {
    //             $query->where('status', '1');
    //         });
    //     }, 'student.user'])
    //     ->get();
    //  return $parent;
    // }
    // public function displayAllBaby(Request $request)
    // {
    //  $parent = Parentt::where('id', auth()->user()->id)
    //     // ->whereHas('student.user', function ($query) {
    //     //     $query->where('status', '1');
    //     // })
    //     ->with(['student' => function ($query) {
    //         $query->whereHas('user', function ($query) {
    //             $query->where('status', '1');
    //         });
    //     }, 'student.user','student.classs','student.section'])
    //     ->get();
    //  return $parent;
    // }
    public function displayAllBaby(Request $request)
{
    // Fetch the parent and associated data
    $parent = Parentt::where('id', auth()->user()->id)
        ->with(['student' => function ($query) {
            $query->whereHas('user', function ($query) {
                $query->where('status', '1');
            });
        }, 'student.user', 'student.classs', 'student.section'])
        ->get();

    // Assign the image_file_url to each student's user data
    foreach ($parent as $p) {
        foreach ($p->student as $student) {
            if (isset($student->user->image)) {
                // Set the image file URL
                $student->user->image_file_url = asset('/upload/' . $student->user->image);
            }
        }
    }

    return $parent;
}




    //برنامج الدوام الخاص بالابن المحدد
    // public function displayPrograme($student_id)
    // {
    //     $student = Student::where('id', $student_id)->first();
    //     $section_id = $student->section_id;
    // $programe = Program_Student::all();

    // if ($programe) {
    //     $result = [];

    //     foreach ($programe as $p) {
    //         if ($p->section_id == $student->section_id) {
    //             $img = Image::all();
    //             foreach ($img as $i) {
    //                 if ($p->id == $i->program_student_id) {
    //                     $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->path);
    //                     if (file_exists($imagePath)) {
    //                         $i->image_url = asset('/upload/' . $i->path);
    //                         $result[] = [
    //                             'info_program' => $p,
    //                             'image_info' => $i
    //                         ];
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     if (!empty($result)) {
    //         return response()->json([
    //             'status' => 'true',
    //             'images' => $result
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 'false',
    //             'message' => 'No images found'
    //         ]);
    //     }
    // } else {
    //     return response()->json([
    //         'status' => 'false',
    //         'message' => 'Program not found for this student'
    //     ]);
    // }
    // }

    public function programe_week($student_id)
{
    $student = Student::where('id', $student_id)->first();

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

    //عرض مواد ابني
    public function displaySubjectSun($student_id)
    {
        $student = Student::where('id', $student_id)->first();
        $subject = Subject::where('class_id', $student->class_id)->get();
        return $subject;
    }

    //عرض وظائف ابني لمادة محددة
    // public function homework_subject_my_sun($student_id, $subject_id)
    // {
    //     // $student = Student::where('id', $student_id)->with('user')->first();
    //     // $year = $student->user->year;
    //     // $homework = Homework::where('year',$year)->where('subject_id', $subject_id)->with('accessories')->get();        
    //     // return $homework;
        

    //     $student = Student::where('id', $student_id)->first();
    //     $year = $student->user->year;

    //     $homework = Homework::where('year',$year)->where('subject_id', $subject_id)->get();
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


//     public function homework_subject_my_sun($student_id,$subject_id)
// {
//     $student = Student::where('id', $student_id)->first();
//     $year = $student->user->year;
//     $homework = Homework::where('year',$year)->where('subject_id', $subject_id)->get();
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

//شغال تمام لكن تم توقيفه بسبب طلب فصل الوظائف عن الملحقات
// public function homework_subject_my_sun($student_id,$subject_id)
// {
//     $student = Student::where('id', $student_id)->first();
//     $year = $student->user->year;

//     $homework = Homework::where('year', $year)->where('subject_id', $subject_id)->get();
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
//                 $a->image_url = asset('/upload/' . $a->path);
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


public function homework_subject_my_sun($student_id,$subject_id)
{
    $student = Student::where('id', $student_id)->first();
    $year = $student->user->year;

    // $homework = Homework::where('year', $year)->where('subject_id', $subject_id)->with('subject')->get();

    $homework = Homework::where('year', $year)->where('subject_id', $subject_id)->with('subject')->with('accessories')->get();

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

    //عرض الملاحظات التي بحق الابن
    public function display_note($student_id)
    {
         $note= Note_Student::where('student_id', $student_id)->with('user')->get();
         return $note;
    }

    //عرض علامات الابن
    public function displayMark($student_id)
    {
        $mark = Mark::where('student_id', $student_id)->with('subject')->get();
        return $mark;
    }


    public function edit_some_info_profile_parent(Request $request)
{
    $validator = Validator::make($request->all(), [
        'address' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    if ($validator->fails()) {
        return $this->responseError(['errors' => $validator->errors()]);
    }

    $parent = Parentt::where('id', auth()->user()->id)->first();

    if ($request->has('phone') && !empty($request->phone)) {
        $phone = $request->phone;
        if (!preg_match('/^(\+?963|0)?9\d{8}$/', $phone)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Syrian phone number'], 400);
        }
        $parent->phone = $request->phone;
    }

    if ($request->has('address') && !empty($request->address)) {
        $parent->address = $request->address;
    }

    if ($request->has('password') && !empty($request->password)) {
        if (!$request->has('conf_password') || $request->password !== $request->conf_password) {
            return response()->json(['status' => 'error', 'message' => 'Passwords do not match'], 400);
        }
        $parent->password = Hash::make($request->password);
        $parent->conf_password = Hash::make($request->conf_password);
    }

    if ($request->hasFile('image')) {
        if ($parent->image != null) {
            $oldImagePath = public_path().'/upload/'.$parent->image;
    if (file_exists($oldImagePath)) {
        unlink($oldImagePath);
    }

    // رفع الصورة الجديدة
    $img = $request->image;
    $ext = $img->getClientOriginalExtension();
    $imageName = time().'.'.$ext;
    $img->move(public_path().'/upload', $imageName);

    // تحديث مسار الصورة في قاعدة البيانات
    $parent->image = $imageName;
        }

        else {
            $img = $request->image;
    $ext = $img->getClientOriginalExtension();
    $imageName = time().'.'.$ext;
    $img->move(public_path().'/upload',$imageName);

    $parent->image = $imageName;
        }
    }

    $parent->save();

    return response()->json(['status' => 'success', 'message' => 'Profile updated successfully', 'user' => $parent]);
}

//الكورسات يلي مسجل فيها ابني
public function course_my_sun($student_id)
    {
        $order = Order::where('student_id', $student_id)->with('course.subject')->with('course.teacher.user')->get();

        return $order;
    }


}
