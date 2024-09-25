<?php

namespace App\Http\Controllers\Api_out_user;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Teacher_subject;
use App\Models\Subject;
use App\Models\User;
use App\Models\Academy;
use App\Models\Fee_School;

class DisplayController extends BaseController
{
    /*------------------------teacher------------------------*/
    public function all_teatcher()
    {
        // $teatcher = Teacher::with('user')->get();
        // return $teatcher;

        $academy = Academy::find(1);
        $teachers = User::where('user_type', 'teacher')->where('year',$academy->year)
                    ->with('teacher.subject')
                    ->orderBy('first_name')
                    ->orderBy('last_name')
                    ->orderBy('father_name')
                    ->get();
        return $teachers;

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

    /*------------------------course------------------------*/
    // public function all_course()
    // {
    //     $course = Course::with('subject')->with('classs')->with('teacher.user')->get();
    //     return $course;
    // }

public function all_course()
{
    $courses = Course::with('subject')
        ->with('classs')
        ->with('teacher.user')
        ->orderByRaw("CASE WHEN Course_status = 1 THEN 0 WHEN Course_status = 2 THEN 1 ELSE 2 END") // ترتيب حسب أولوية القيم
        ->orderBy('created_at', 'desc') // ترتيب حسب تاريخ الإنشاء بتنازلي
        ->get();

    return $courses;
}

    public function info_course($id_course)
    {
        $course = Course::where('id', $id_course)->with('subject')->with('classs')->with('teacher.user')->get();
        return $course;
    }

//     //عرض الإعلانات
//     public function publish()
//     {
//     //     //عرض إعلانات
//     // Route::get('/publish',[DisplayController::class,'publish']);
// $publish = Publish::all();
// $result = [];

// foreach ($publish as $p) {
//     $images = Image::where('publish_id', $p->id)->get();
//     $imageData = [];

//     foreach ($images as $i) {
//         $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->path);
        
//         if (file_exists($imagePath)) {
//             $imageData[] = [
//                 'path' => $imagePath,
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

public function display_fee_class()
{
    $academy = $academy = Academy::find(1);
    $fee = Fee_School::where('year', $academy->year)->with('classs')->get();

    return $fee;

}
}
