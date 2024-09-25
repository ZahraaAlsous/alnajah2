<?php

namespace App\Http\Controllers\Api_teacher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Program_Teachar;
use App\Models\Student;
use App\Models\Note_Student;
use App\Models\Out_Of_Work_Employee;
use App\Models\User;
use App\Models\Archive;
use App\Models\Section;
use App\Models\Teacher_section;
use App\Models\Mark;
use App\Models\Teacher_subject;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
use App\Models\Image;
use App\Models\Image_Archive;
use App\Models\File_Archive;
use Illuminate\Http\UploadedFile;
use App\Models\Academy;
use Illuminate\Support\Facades\Hash;
use App\Models\File_course;
use App\Models\Teacher_Schedule;
use Illuminate\Support\Facades\DB;
use App\Models\Homework;
use App\Models\Accessories;
use Illuminate\Support\Carbon;
use App\Models\Hour_Added;
use App\Models\course;
use App\Models\Order;
use App\Models\Expenses;




class TeacherController extends BaseController
{
    //عرض برنامج الدوام الأستاذ
    public function programe()
{
    $teacher = Teacher::where('user_id', auth()->user()->id)->first();
    $programe = Program_Teachar::where('teacher_id',$teacher->id)->first();

    if ($programe) {
                    $img = Image::where('program_teacher_id', $programe->id)->latest()->first();
                    //return $img;
                        $imagePath = str_replace('\\', '/', public_path().'/upload/'.$img->path);
                        //return response()->file($imagePath);
                        if (file_exists($imagePath)) {
                            $img->image_url = asset('/upload/' . $img->path);
                            return response()->json([
                                // 'path' => $imagePath,
                                'image_info' => $img
                            ]);    
                        }
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'No images found'
            ]);
        }
}

    //إضافة ملاحظات لطالب معين
    public function add_note_about_student(Request $request ,$student_id, NotificationController $notificationController)
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

        $note = new Note_Student;

        $note->type = $request->type;
        $note->text = $request->text;
        $note->student_id = $student_id;
        $note->user_id = auth()->user()->id;

        if ($note->save()) {
            $title = $note->type.'للطالب '. $student->user->first_name .' '. $student->user->last_name;
            $title_s = $note->type;
            $body = $note->text;

            $notificationController->sendNotification_for_parent($title,$body,$student_id);
            $notificationController->sendNotification_call($student->user->fcm_token, $title_s, $body);
                }

        return $note;
    }

    //غيابات المدرس
    public function out_work_teacher()
    {
        $teacher = Teacher::where('user_id', auth()->user()->id)->first();

        $out_work = Out_Of_Work_Employee::where('teacher_id', $teacher->id)->get();
        return $out_work;
    }

    //عرض المواد مع الصف التي أدرسها
    public function display_supject_with_class()
    {
        $teacher = Teacher::where('user_id', auth()->user()->id)->first();
        $teacher_subject = Teacher_subject::where('teacher_id', $teacher->id)->get();

        foreach ($teacher_subject as $subject) {
            $info_subject = Subject::where('id', $subject->subject_id)->with('classs')->get();
            $result[] = $info_subject;
        }
        return $result;

    }

    //عرض المواد التي أدرسها
    public function display_supject()
    {
        $teacher = Teacher::where('user_id', auth()->user()->id)->first();
        $teacher_subject = Teacher_subject::where('teacher_id', $teacher->id)->get();

        foreach ($teacher_subject as $subject) {
            $info_subject = Subject::where('id', $subject->subject_id)->get();
            $result[] = $info_subject;
        }
        return $result;

    }

//     public function display_file_image_subject($subject_id)
// {
//     $user= User::where('id',auth()->user()->id)->first();
//     if (!$user) {
//         return response()->json(['error' => 'user not found'], 404);
//     }

//     $archive = Archive::where('year',$user->year)->where('subject_id', $subject_id)->first();

//     if (!$archive) {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'this subject do not have archive'
//         ]);
//     }
//     //صور السنة الحالية للمادة المحددة
//     $image_select_year = Image_Archive::where('archive_id',$archive->id)->orderBy('created_at', 'desc')->get();
//     foreach ($image_select_year as $i) {
//         $imagePath = str_replace('\\', '/', public_path().'/upload/'.$i->name);
//                     //return response()->file($imagePath);
//                     if (file_exists($imagePath)) {
//                         $i->image_url = asset('/upload/' . $i->name);
//                         $result[] = [
//                             // 'path' => $imagePath,
//                             // 'image_info' => $i
//                             $i
//                         ];    
//                     }
//     }

//     //صور السنة الحالية للمادة المحددة
//     $file_select_year = File_Archive::where('archive_id',$archive->id)->orderBy('created_at', 'desc')->get();
//     foreach ($file_select_year as $f) {
//         $filePath = str_replace('\\', '/', public_path().'/upload/'.$f->name);
//                     //return response()->file($imagePath);
//                     if (file_exists($filePath)) {
//                         $f->file_url = asset('/upload/' . $f->name);
//                         $result[] = [
//                             // 'path' => $filePath,
//                             // 'file_info' => $f
//                             $f
//                         ];    
//                     }
//     }
//     //عم نشوف إذا في نتائج أو لاء
//     if (!empty($result)) {
//         // return response()->json([
//         //     'status' => 'true',
//         //     'images_files' => $result
//         // ]);
        
//         return $result;
//     } else {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'No images found'
//         ]);
//     }

// }

// public function display_file_image_subject($subject_id)
// {
//     $user = User::where('id', auth()->user()->id)->first();
//     if (!$user) {
//         return response()->json(['error' => 'user not found'], 404);
//     }

//     $archive = Archive::where('year', $user->year)->where('subject_id', $subject_id)->first();

//     if (!$archive) {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'this subject does not have an archive'
//         ]);
//     }

//     // صور السنة الحالية للمادة المحددة
//     $image_select_year = Image_Archive::where('archive_id', $archive->id)->get();
//     $file_select_year = File_Archive::where('archive_id', $archive->id)->get();

//     $result = [];

//     // إضافة الصور إلى المصفوفة
//     foreach ($image_select_year as $image) {
//         $imagePath = str_replace('\\', '/', public_path() . '/upload/' . $image->name);
//         if (file_exists($imagePath)) {
//             $image->image_url = asset('/upload/' . $image->name);
//             $result[] = [
//                 'type' => 'image',
//                 'data' => $image,
//                 'created_at' => $image->created_at // إضافة تاريخ الإنشاء
//             ];
//         }
//     }

//     // إضافة الملفات إلى المصفوفة
//     foreach ($file_select_year as $file) {
//         $filePath = str_replace('\\', '/', public_path() . '/upload/' . $file->name);
//         if (file_exists($filePath)) {
//             $file->file_url = asset('/upload/' . $file->name);
//             $result[] = [
//                 'type' => 'file',
//                 'data' => $file,
//                 'created_at' => $file->created_at // إضافة تاريخ الإنشاء
//             ];
//         }
//     }

//     // ترتيب النتائج حسب تاريخ الإنشاء (الأحدث أولاً)
//     usort($result, function ($a, $b) {
//         return strtotime($b['created_at']) - strtotime($a['created_at']);
//     });

//     // عم نشوف إذا في نتائج أو لا
//     if (!empty($result)) {
//         // return response()->json([
//         //     'status' => 'true',
//         //     'images_files' => $result
//         // ]);
//         return $result;
//     } else {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'No images or files found'
//         ]);
//     }
// }

public function display_file_image_subject($subject_id)
{
    $user = User::where('id', auth()->user()->id)->first();
    if (!$user) {
        return response()->json(['error' => 'user not found'], 404);
    }

    $archive = Archive::where('year', $user->year)->where('subject_id', $subject_id)->first();

    if (!$archive) {
        return response()->json([
            'status' => 'false',
            'message' => 'this subject does not have an archive'
        ]);
    }

    // صور السنة الحالية للمادة المحددة
    $image_select_year = Image_Archive::where('archive_id', $archive->id)->get();
    $file_select_year = File_Archive::where('archive_id', $archive->id)->get();

    $result = [
        'images' => [],
        'files' => []
    ];

    // إضافة الصور إلى المصفوفة
    foreach ($image_select_year as $image) {
        $imagePath = str_replace('\\', '/', public_path() . '/upload/' . $image->name);
        if (file_exists($imagePath)) {
            $image->image_url = asset('/upload/' . $image->name);
            $result['images'][] = [
                'id' => $image->id,
                'name' => $image->name,
                'description' => $image->description,
                'archive_id' => $image->archive_id,
                'created_at' => $image->created_at,
                'updated_at' => $image->updated_at,
                'image_url' => $image->image_url
            ];
        }
    }

    // إضافة الملفات إلى المصفوفة
    foreach ($file_select_year as $file) {
        $filePath = str_replace('\\', '/', public_path() . '/upload/' . $file->name);
        if (file_exists($filePath)) {
            $file->file_url = asset('/upload/' . $file->name);
            $result['files'][] = [
                'id' => $file->id,
                'name' => $file->name,
                'description' => $file->description,
                'archive_id' => $file->archive_id,
                'created_at' => $file->created_at,
                'updated_at' => $file->updated_at,
                'file_url' => $file->file_url
            ];
        }
    }

    // دمج الصور والملفات في مصفوفة واحدة مرتبة حسب تاريخ الإنشاء (الأحدث أولاً)
    $finalResult = array_merge($result['images'], $result['files']);
    
    usort($finalResult, function ($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    // تنسيق النتيجة النهائية
    $formattedResult = [];
    foreach ($finalResult as $item) {
        if (isset($item['image_url'])) {
            $formattedResult[] = [$item]; // إذا كان عنصر صورة
        } else {
            $formattedResult[] = [$item]; // إذا كان عنصر ملف
        }
    }

    // عم نشوف إذا في نتائج أو لا
    if (!empty($formattedResult)) {
        return response()->json($formattedResult);
    } else {
        return response()->json([
            'status' => 'false',
            'message' => 'No images or files found'
        ]);
    }
}





    //عرض ملفات المادة التي يعطيها للسنة الحالية
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
                            // 'file_info' => $f
                            $f
                        ];    
                    }
    }
    //عم نشوف إذا في نتائج أو لاء
    if (!empty($result)) {
        // return response()->json([
        //     'status' => 'true',
        //     'images_files' => $result
        // ]);
        
        return $result;
    } else {
        return response()->json([
            'status' => 'false',
            'message' => 'No images found'
        ]);
    }

}

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
                            // 'image_info' => $i
                            $i
                        ];    
                    }
    }

    //عم نشوف إذا في نتائج أو لاء
    if (!empty($result)) {
        // return response()->json([
        //     'status' => 'true',
        //     'images_files' => $result
        // ]);
        
        return $result;
    } else {
        return response()->json([
            'status' => 'false',
            'message' => 'No images found'
        ]);
    }

}



//حذف ملف أو صورة من ملفات السنة الحالية أو الأرشيفzahraa
public function delete_file_image($file_img_id, $imgFileName)
{
    $img = Image_Archive::find($file_img_id);
    $file = File_Archive::find($file_img_id);

    if ($img != null && $img->name == $imgFileName) {
        // $img->delete();
        // return "delete image";

        // $image = Image::findOrFail($id);

    // حذفت الملف من المجلد يلي خزنتو في
    $imagePath = public_path().'/upload/'.$img->name;
    if(file_exists($imagePath)) {
        unlink($imagePath);
    }

    // حذفت الملف من الداتا عندي
    $img->delete();

    return response()->json([
        'status' => 'true',
        'message' => 'Image deleted successfully'
    ]);
    }
    elseif ($file != null && $file->name == $imgFileName) {
        // $file->delete();
        // return "delete file";
        // حذفت الملف من المجلد يلي خزنتو في
    $filePath = public_path().'/upload/'.$file->name;
    if(file_exists($filePath)) {
        unlink($filePath);
    }

    // حذفت الملف من الداتا عندي
    $file->delete();

    return response()->json([
        'status' => 'true',
        'message' => 'File deleted successfully'
    ]);
    } 
        return "you do not have";

}

//رفع ملفات و صور للسنة الحاليةzahraa
public function upload_file_image(Request $request, $subject_id)
{
    $year_study = Academy::find(1);
    
    //إذا الأرشيف مو موجود بساوي واحد
    if (!$archive = Archive::where('year',$year_study->year)->where('subject_id', $subject_id)->first()) {
        $archive = new Archive;

        $archive->year = $year_study->year;
        $archive->subject_id = $subject_id;
        $subject = Subject::where('id',$subject_id)->first();
        $archive->class_id = $subject->class_id;
        $archive->save();

    }
    $validator = Validator::make($request->all(),[
        'name' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt',
        'description' => 'required',
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

    if ($ext=="png" || $ext=="jpg" || $ext=="jpeg" || $ext=="gif") {
        $image = new Image_Archive;
    $image->name = $imgFileName;
    $image->description = $request->description;
    $image->archive_id = $archive->id;
    $image->save();

    return response()->json([
        'status' => 'true',
        'message' => 'image upload success',
        'path' => asset('/upload/'.$imgFileName),
        'data' => $image
    ]);
    }
    
    elseif ($ext=="pdf" || $ext=="docx" || $ext=="txt") {
        $file = new File_Archive;
    $file->name = $imgFileName;
    $file->description = $request->description;
    $file->archive_id = $archive->id;
    $file->save();

    return response()->json([
        'status' => 'true',
        'message' => 'file upload success',
        'path' => asset('/upload/'.$imgFileName),
        'data' => $file
    ]);
    }

}

public function update_file_image(Request $request, $id)
{
    // التحقق من صحة البيانات المدخلة
    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|mimes:png,jpg,jpeg,gif,pdf,docx,txt',
        'description' => 'sometimes|string',
        'archive_id' => 'sometimes|integer'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'false',
            'message' => 'Please fix the errors',
            'errors' => $validator->errors()
        ]);
    }

    // البحث عن الملف أو الصورة في قاعدة البيانات
    $image = Image_Archive::find($id);
    $file = File_Archive::find($id);
    
    

    if (!$image && !$file) {
        return response()->json([
            'status' => 'false',
            'message' => 'File or image not found'
        ]);
    }

    // إذا تم تقديم ملف جديد
if ($request->hasFile('name')) {
    $img = $request->name;
    $ext = $img->getClientOriginalExtension();
    $imgFileName = time() . '.' . $ext;
    $img->move(public_path() . '/upload', $imgFileName);

    // تحديث السجل بناءً على النوع (صورة أو ملف)
    if ($image) {
        $extension = $image->getClientOriginalExtension();
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    if ($request->has('description')) {
            $image->description = $request->description;
        }
        if ($request->has('archive_id')) {
            $image->archive_id = $request->archive_id;
        }
        
        $image->save();

        return response()->json([
            'status' => 'true',
            'message' => 'Image updated successfully',
            'data' => $image
        ]);
    }
} elseif ($file) {
    $extension = $file->getClientOriginalExtension();
    if (in_array($extension, ['pdf', 'doc', 'docx', 'txt'])) {
        if ($request->has('description')) {
            $file->description = $request->description;
        }
        if ($request->has('archive_id')) {
            $file->archive_id = $request->archive_id;
        }
        $file->save();
        
        return response()->json([
            'status' => 'true',
            'message' => 'File updated successfully',
            'data' => $file
        ]);
    }
}
}
//     if ($image) {
//         // تحديث السجل في جدول الصور
//         $image->name = $imgFileName;
//         $image->save();
//     } elseif ($file) {
//         // تحديث السجل في جدول الملفات
//         $file->name = $imgFileName;
//         $file->save();
//     }
// }

// // تحديث السجل بناءً على النوع (صورة أو ملف)
// if ($image) {
//     $ext_img = pathinfo($image->name, PATHINFO_EXTENSION);
//     if (in_array($ext_img, ['png', 'jpg', 'jpeg', 'gif'])) {
//         $ext_img = pathinfo($image->name, PATHINFO_EXTENSION);
//         if (($ext_img=="png" || $ext_img=="jpg" || $ext_img=="jpeg" || $ext_img=="gif") || ($ext=="png" || $ext=="jpg" || $ext=="jpeg" || $ext=="gif")) {
//             if ($request->has('description')) {
//                 $image->description = $request->description;
//             }
//             if ($request->has('archive_id')) {
//                 $image->archive_id = $request->archive_id;
//             }
            
//             $image->save();
    
//             return response()->json([
//                 'status' => 'true',
//                 'message' => 'Image updated successfully',
//                 'data' => $image
//             ]);
//         }
//     }
// } elseif ($file) {
//     $ext_file = pathinfo($file->name, PATHINFO_EXTENSION);
//     if (in_array($ext_file, ['pdf', 'docx', 'txt'])) {
//         $ext_file = pathinfo($file->name, PATHINFO_EXTENSION);
//             if (($ext_file=="pdf" || $ext_file=="docx" || $ext_file=="txt") || ($ext=="pdf" || $ext=="docx" || $ext=="txt")) {
//                 if ($request->has('description')) {
//                     $file->description = $request->description;
//                 }
//                 if ($request->has('archive_id')) {
//                     $file->archive_id = $request->archive_id;
//                 }
//                 $file->save();
        
//                 return response()->json([
//                     'status' => 'true',
//                     'message' => 'File updated successfully',
//                     'data' => $file
//                 ]);
//             }
//     }
// }


    // // إذا تم تقديم ملف جديد
    // if ($request->hasFile('name')) {
    //     $img = $request->name;
    //     $ext = $img->getClientOriginalExtension();
    //     $imgFileName = time() . '.' . $ext;
    //     $img->move(public_path() . '/upload', $imgFileName);

    //     if ($image) {
    //         $image->name = $imgFileName;
    //     } elseif ($file) {
    //         $file->name = $imgFileName;
    //     }
    // }

    // // تحديث السجل بناءً على النوع (صورة أو ملف)
    // if ($image) {
    //     $ext_img = pathinfo($image->name, PATHINFO_EXTENSION);
    //     if (($ext_img=="png" || $ext_img=="jpg" || $ext_img=="jpeg" || $ext_img=="gif") || ($ext=="png" || $ext=="jpg" || $ext=="jpeg" || $ext=="gif")) {
    //         if ($request->has('description')) {
    //             $image->description = $request->description;
    //         }
    //         if ($request->has('archive_id')) {
    //             $image->archive_id = $request->archive_id;
    //         }
            
    //         $image->save();
    
    //         return response()->json([
    //             'status' => 'true',
    //             'message' => 'Image updated successfully',
    //             'data' => $image
    //         ]);
    //     }
    //     elseif ($file) {
    //         $ext_file = pathinfo($file->name, PATHINFO_EXTENSION);
    //         if (($ext_file=="pdf" || $ext_file=="docx" || $ext_file=="txt") || ($ext=="pdf" || $ext=="docx" || $ext=="txt")) {
    //             if ($request->has('description')) {
    //                 $file->description = $request->description;
    //             }
    //             if ($request->has('archive_id')) {
    //                 $file->archive_id = $request->archive_id;
    //             }
    //             $file->save();
        
    //             return response()->json([
    //                 'status' => 'true',
    //                 'message' => 'File updated successfully',
    //                 'data' => $file
    //             ]);
            // }
            
            
        // } 
    // } 
}

// public function update_file_image(Request $request, $id)
// {
//     // التحقق من صحة البيانات المدخلة
//     $validator = Validator::make($request->all(),[
//         'description' => 'sometimes|string',
//         'archive_id' => 'sometimes|integer'
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'Please fix the errors',
//             'errors' => $validator->errors()
//         ]);
//     }

//     // البحث عن الملف أو الصورة في قاعدة البيانات
//     $image = Image_Archive::find($id);
//     $file = File_Archive::find($id);
//     pathinfo($image->name, PATHINFO_EXTENSION);

//     if (!$image && !$file) {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'File or image not found'
//         ]);
//     }

//     // تحديث السجل بناءً على النوع (صورة أو ملف)
//     if ($image) {
//         if ($request->has('description')) {
//             $image->description = $request->description;
//         }
//         if ($request->has('archive_id')) {
//             $image->archive_id = $request->archive_id;
//         }
//         $image->save();

//         return response()->json([
//             'status' => 'true',
//             'message' => 'Image updated successfully',
//             'data' => $image
//         ]);
//     } elseif ($file) {
//         if ($request->has('description')) {
//             $file->description = $request->description;
//         }
//         if ($request->has('archive_id')) {
//             $file->archive_id = $request->archive_id;
//         }
//         $file->save();

//         return response()->json([
//             'status' => 'true',
//             'message' => 'File updated successfully',
//             'data' => $file
//         ]);
//     }
// }




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

        //صور السنة الحالية للمادة المحددة
        $file_select_year = File_Archive::where('archive_id',$archive->id)->get();
        foreach ($file_select_year as $f) {
            $filePath = str_replace('\\', '/', public_path().'/upload/'.$f->name);
                        //return response()->file($imagePath);
                        if (file_exists($filePath)) {
                            $f->image_url = asset('/upload/' . $f->name);
                            $result[] = [
                                // 'path' => $filePath,
                                // 'file_info' => $f
                                $f
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
                'message' => 'No files found'
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
                                    // 'image_info' => $i
                                    $i
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
    

//رفع ملف أو صورة من ملفات الأرشيف
public function upload_file_image_archive(Request $request, $archive_id)
{
    // public function upload_file_image(Request $request, $subject_id)
    $validator = Validator::make($request->all(),[
        'name' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt',
        'description' => 'required',
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

    if ($ext=="png" || $ext=="jpg" || $ext=="jpeg" || $ext=="gif") {
        $image = new Image_Archive;
    $image->name = $imgFileName;
    $image->description = $request->description;
    $image->archive_id = $archive_id;
    $image->save();

    return response()->json([
        'status' => 'true',
        'message' => 'image upload success',
        'path' => asset('/upload/'.$imgFileName),
        'data' => $image
    ]);
    }
    
    elseif ($ext=="pdf" || $ext=="docx" || $ext=="txt") {
        $file = new File_Archive;
    $file->name = $imgFileName;
    $file->description = $request->description;
    $file->archive_id = $archive_id;
    $file->save();

    return response()->json([
        'status' => 'true',
        'message' => 'file upload success',
        'path' => asset('/upload/'.$imgFileName),
        'data' => $file
    ]);
    
}
}

//الصفوف الذي يعطيه المدرس
public function classs()
{
        $teacher = Teacher::where('user_id', auth()->user()->id)->first();
        $teacher_subject = Teacher_subject::where('teacher_id', $teacher->id)->get();
        foreach ($teacher_subject as $subject) {
           $info_subject = Subject::find($subject->subject_id);
            $result[] = $info_subject->classs;
        }
        return $result;
 
}

public function suction($class_id)
{
     $teacher = Teacher::where('user_id', auth()->user()->id)->first();
    $all_section_class = Section::where('class_id', $class_id)->get();
    $class = Teacher_section::where('teacher_id',$teacher->id)->get();
    
    $result = [];
    foreach ($all_section_class as $section) {
        $section_id = $section->id;
        foreach ($class as $sectionclass)
        {
            $sectionclass_id = $sectionclass->section_id;
            if ($section_id == $sectionclass_id) {
                //echo $section;
                $result[] = $section;
            }
        }
        
    }

    return $result;
}

//عرض طلاب شعبة محددة
public function display_student_section($section_id)
{
    $student = Student::where('section_id', $section_id)->with('user')->get();

    return $student;
}

//عرض معلومات طالب
public function display_info_student($student_id)
{
    $student = Student::where('id', $student_id)->with('user')->first();
    return $student;
    // $student = Student::with('user')->find($student_id);
    //     if(!$student)
    //     {
    //         return response()->json('the student not found ');
    //     }

    //     return response()->json(['student' => $student, 'message' => 'Success']);
}

//عرض علامات طالب لمادة محددة حسب المادة التي يعطيها المدرس
public function display_mark($student_id)
{
    $teacher = Teacher::where('user_id', auth()->user()->id)->with('subject')->first();

    if ($teacher && $teacher->subject) {
        $mark = Mark::where('student_id', $student_id)->where('subject_id', $teacher->subject->id)->first();
        return $mark;
    }

    return null;
}

public function add_mark_to_student(Request $request, $student_id, NotificationController $notificationController)
{
    // التحقق من وجود الطالب
    $student = Student::find($student_id);
    
    if (!$student) {
        return response()->json(['error' => 'The student not found'], 404);
    }

    // التحقق من صحة البيانات المدخلة
    $validator = Validator::make($request->all(), [
        'subject_id' => 'required|integer',
        'ponus' => 'nullable|numeric',
        'homework' => 'nullable|numeric',
        'oral' => 'nullable|numeric',
        'test1' => 'nullable|numeric',
        'test2' => 'nullable|numeric',
        'exam_med' => 'nullable|numeric',
        'exam_final' => 'nullable|numeric',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // البحث عن العلامة بناءً على الطالب والمادة
    $mark = Mark::where('student_id', $student_id)
                ->where('subject_id', $request->input('subject_id'))
                ->first();

    // إذا كانت العلامة موجودة، يتم تحديثها
    if ($mark) {
        if ($request->has('ponus') && !empty($request->ponus)) {
            $mark->ponus = $request->ponus;
        }

        if ($request->has('homework') && !empty($request->homework)) {
            $mark->homework = $request->homework;
            $body =$request->homework;
        }

        if ($request->has('oral') && !empty($request->oral)) {
            $mark->oral = $request->oral;
        }

        if ($request->has('test1') && !empty($request->test1)) {
            $mark->test1 = $request->test1;
        }

        if ($request->has('test2') && !empty($request->test2)) {
            $mark->test2 = $request->test2;
        }

        if ($request->has('exam_med') && !empty($request->exam_med)) {
            $mark->exam_med = $request->exam_med;
        }

        if ($request->has('exam_final') && !empty($request->exam_final)) {
            $mark->exam_final = $request->exam_final;
        }
    } 
    // إذا لم تكن العلامة موجودة، يتم إنشاء صف جديد
    else {
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
    }

    // حساب المجموع
    $aggregate = ($mark->ponus ?? 0) + ($mark->homework ?? 0) + ($mark->oral ?? 0) + ($mark->test1 ?? 0) + ($mark->test2 ?? 0) + ($mark->exam_med ?? 0) + ($mark->exam_final ?? 0);

    // تحديد حالة الطالب (ناجح/راسب)
    $mark->state = ($aggregate >= 50) ? 1 : 0;

    if ($mark->exam_final == 0) {
        $mark->state = 0;
    }

    if ($mark->save()) {
        $subject = Subject::find($mark->subject_id);
        $title = 'علامة جديدة';
        $body ='علامة جديدة في مادة '. $subject->name.' للطالب '. $student->user->first_name .' '. $student->user->last_name;
        $body_s = 'علامة جديدة في مادة ' . $subject->name;
        
        $notificationController->sendNotification_for_parent($title,$body,$student_id);
        $notificationController->sendNotification_call($student->user->fcm_token, $title, $body_s);
}

    return response()->json(['success' => 'Marks added/updated successfully']);
}

//تعديل علامة طالب
public function edit_mark(Request $request,$mark_id)
{
    $mark = Mark::where('id', $mark_id)->first();
    if ($request->has('ponus') && !empty($request->ponus)) {
        $mark->ponus = $request->ponus;
    }

    if ($request->has('homework') && !empty($request->homework)) {
        $mark->homework = $request->homework;
    }

    if ($request->has('oral') && !empty($request->oral)) {
        $mark->oral = $request->oral;
    }

    if ($request->has('test1') && !empty($request->test1)) {
        $mark->test1 = $request->test1;
    }

    if ($request->has('test2') && !empty($request->test2)) {
        $mark->test2 = $request->test2;
    }

    if ($request->has('exam_med') && !empty($request->exam_med)) {
        $mark->exam_med = $request->exam_med;
    }

    if ($request->has('exam_final') && !empty($request->exam_final)) {
        $mark->exam_final = $request->exam_final;
    }

    // حساب المجموع
    $aggregate = ($mark->ponus ?? 0) + ($mark->homework ?? 0) + ($mark->oral ?? 0) + ($mark->test1 ?? 0) + ($mark->test2 ?? 0) + ($mark->exam_med ?? 0) + ($mark->exam_final ?? 0);

    // تحديد حالة الطالب (ناجح/راسب)
    $mark->state = ($aggregate >= 50) ? 1 : 0;

    if ($mark->exam_final == 0) {
        $mark->state = 0;
    }

    $aggregate = ($mark->ponus ?? 0) + ($mark->homework ?? 0) + ($mark->oral ?? 0) + ($mark->test1 ?? 0) + ($mark->test2 ?? 0) + ($mark->exam_med ?? 0) + ($mark->exam_final ?? 0);

    // تحديد حالة الطالب (ناجح/راسب)
    $mark->state = ($aggregate >= 50) ? 1 : 0;

    if ($mark->exam_final == 0) {
        $mark->state = 0;
    }
    $mark->student_id = $mark->student_id;
    $mark->subject_id = $mark->subject_id; 
    
    $mark->save();
    return $mark;

}


//عرض جميع الطلاب الذي يدرسهم حسب الترتيب الأبجدي
// public function display_all_students_I_teach()
// {
//     $academy= $academy = Academy::find(1);
//     $teacher = Teacher::where('user_id', auth()->user()->id)->first();
//     $sections = Teacher_section::where('teacher_id', $teacher->id)->get();
//     $students = collect();

//     foreach ($sections as $section) {
//         $students = $students->merge(Student::where('section_id', $section->id)->where('user.year',$academy->year)->with('user')->with('section.classs')->get());
//     }

//     $sortedStudents = $students->sortBy(function($student) {
//         return $student->user->first_name;
//     });

//     return $sortedStudents->values()->all();

// //     عرض جميع الطلاب الذين يدرسهم ولكن دون ترتيب أبجدي
// //     $teacher = Teacher::where('user_id', auth()->user()->id)->first();
// //     $sections = Teacher_section::where('teacher_id', $teacher->id)->get();
// //     $students = [];

// //     foreach ($sections as $section) {
// //         $students[] = Student::where('section_id', $section->id)->with('user')->get();
// //     }

// //     return $students;
//  }
public function display_all_students_I_teach()
{
    $academy = Academy::find(1);
    $teacher = Teacher::where('user_id', auth()->user()->id)->first();
    $sections = Teacher_section::where('teacher_id', $teacher->id)->get();
    $students = collect();

    foreach ($sections as $section) {
        // استخدام whereHas للوصول إلى الحقل year من جدول users
        $students = $students->merge(
            Student::where('section_id', $section->id)
            ->whereHas('user', function ($query) use ($academy) {
                $query->where('year', $academy->year);
            })
            ->with('user')
            ->with('section.classs')
            ->get()
        );
    }

    // ترتيب الطلاب أبجدياً بناءً على الاسم الأول
    $sortedStudents = $students->sortBy(function($student) {
        return $student->user->first_name;
    });

    return $sortedStudents->values()->all();
}

























public function upload(Request $request)
{
    //Route::post('/upload',[TeacherController::class,'upload']);
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
    $image->save();

    return response()->json([
        'status' => 'true',
        'message' => 'image upload success',
        'path' => asset('/upload/'.$imageName),
        'data' => $image
    ]);
}


public function showImage($path)
{
    $image = public_path().'/upload/'.$path;

    if (file_exists($image)) {
        return response()->file($image);
    } else {
        return response()->json([
            'status' => 'false',
            'message' => 'Image not found'
        ]);
    }
}


public function delete($id)
{
    $image = Image::findOrFail($id);

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

//تابع تعديل
public function update_image(Request $request, $id)
{
    // التحقق من المدخلات
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
    $image = Image::find($id);

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
    $image->save();

    return response()->json([
        'status' => 'true',
        'message' => 'Image updated successfully',
        'path' => asset('/upload/'.$imageName),
        'data' => $image
    ]);
}


public function edit_some_info_teacher_profile(Request $request)
{
    $validator = Validator::make($request->all(), [
        'address' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    if ($validator->fails()) {
        return $this->responseError(['errors' => $validator->errors()]);
    }

    $user = User::where('id', auth()->user()->id)->with('teacher')->first();

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

    if ($request->has('image')) {
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

    return response()->json(['status' => 'success', 'message' => 'Profile updated successfully', 'teacher' => $user]);
}

public function delete_file_course($file_id)
{
    $file = File_course::where('id',$file_id)->first();

    $imagePath = public_path().'/upload/'.$file->name;
    if(file_exists($imagePath)) {
        unlink($imagePath);
    }

    // حذفت الملف من الداتا عندي
    $file->delete();

    return response()->json([
        'status' => 'true',
        'message' => 'file_image deleted successfully'
    ]);


}

public function show_my_profile()
{
    $user = User::where('id', auth()->user()->id)->with('teacher')->first();

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

public function getWeeklyTeacherSchedule()
{
    $teacher = Teacher::where('user_id', auth()->user()->id)->first();
    // $teacher = Teacher::find($teacher_id);
    if (!$teacher) {
        return response()->json(['message' => 'Teacher not found'], 404);
    }

    // استرجاع الجدول الزمني للأستاذ مع تفاصيل الشعبة
    $schedules = Teacher_Schedule::with('section')
                                ->where('teacher_id', $teacher->id)
                                ->orderBy('day_of_week')
                                ->orderBy('start_time')
                                ->get();

    if ($schedules->isEmpty()) {
        return response()->json(['message' => 'No schedule found for this teacher'], 404);
    }

    // تنظيم الجدول حسب أيام الأسبوع
    $weekly_schedule = [
        'Sunday' => [],
        'Monday' => [],
        'Tuesday' => [],
        'Wednesday' => [],
        'Thursday' => [],
    ];

    foreach ($schedules as $schedule) {
        $weekly_schedule[$schedule->day_of_week][] = [
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'section' => $schedule->section ? $schedule->section->num_section : 'N/A',
        ];
    }

    return response()->json(['weekly_schedule' => $weekly_schedule], 200);
}


public function homework_subject()
{
    // $user = User::where('id', auth()->user()->id)->first();

    // if (!$user) {
    //     return response()->json(['error' => 'user not found'], 404);
    // }

    $teacher = Teacher::where('user_id', auth()->user()->id)->first();
    $subject = DB::table('teacher_subjects')->where('teacher_id','=',$teacher->id)->first(); 

    $homework = Homework::where('year', auth()->user()->year)->where('subject_id', $subject->subject_id)->with('subject')->with('accessories')->get();

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

public function upload_homework(Request $request, NotificationController $notificationController)
{
    $academy = Academy::find(1);
    $teacher = Teacher::where('user_id', auth()->user()->id)->first();
    $subject = DB::table('teacher_subjects')->where('teacher_id','=',$teacher->id)->first();
    $class = Subject::where('id',$subject->id)->first();

    $homework = new Homework();

    $homework->description = $request->description;
    $homework->year = $academy->year;
    $homework->subject_id = $subject->id;
    $homework->class_id = $class->id;
    // $homework->save();
    if ($homework->save()) {
        $title = 'وظيفة '.$homework->subject->name;
        $body = $homework->description;

        $notificationController->sendNotification_student_class($title,$body,$homework->class_id);
    }

    if ($request->path && !empty($request->path)) {
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

            $image = new Accessories;
            $image->path = $imageName;
            $image->discription = $request->description;
            $image->home_work_id = $homework->id;

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

public function delete_homework($homework_id)
{
    $homework = Homework::find($homework_id);

    if (!$homework) {
        return response()->json(['status' => 'false', 'message' => 'Homework not found'], 404);
    }

    $accessories = Accessories::where('home_work_id', $homework->id)->get();

    foreach ($accessories as $accessory) {
        $file_path = public_path('upload/' . $accessory->path);
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $accessory->delete();
    }

    $homework->delete();

    return response()->json(['status' => 'true', 'message' => 'Homework and its accessories deleted successfully']);
}


public function search_student(Request $request)
{
    // الحصول على بيانات الأستاذ
    $teacher = Teacher::where('user_id', auth()->user()->id)->first();

    // الحصول على جميع الأقسام (الشعب) التي يدرسها الأستاذ
    $sections = DB::table('teacher_sections')->where('teacher_id', $teacher->id)->pluck('section_id');

    // البحث عن الطلاب في هذه الأقسام فقط
    $query = User::whereHas('student', function ($q) use ($sections) {
        $q->whereIn('section_id', $sections);
    })->where('user_type', 'student')
      ->where('status', '1');

    // تقسيم مدخل البحث إلى أجزاء بناءً على المسافة
    $keywords = explode(' ', $request->q);

    // إضافة شروط البحث لكل كلمة في الكلمات المفتاحية
    foreach ($keywords as $keyword) {
        $query->where(function ($subQuery) use ($keyword) {
            $subQuery->where('first_name', 'LIKE', "%{$keyword}%")
                     ->orWhere('last_name', 'LIKE', "%{$keyword}%");
        });
    }

    // تنفيذ الاستعلام
    $students = $query->with('student.classs', 'student.section')->get();

    return response()->json($students);
}


public function desplay_maturitie_for_teacher($year, $month)
{
    // $teacher = Teacher::find($teacher_id);
    // if (!$teacher) {
    //     return response()->json(['the teacher not found']);
    // }

    $user = User::find(auth()->user()->id);
    $teacher = $user->teacher()->first();

    // حساب عدد ساعات العمل والراتب الأساسي
    $num_work_hour = $this->getteacherworkhour($teacher->id, $year, $month);
    $basic_salary = $num_work_hour * $teacher->cost_hour;

    // الحصول على السلف لهذا الشهر فقط
    $solfa = 0;
    $maturities = $teacher->maturitie()
        ->whereYear('updated_at', $year)
        ->whereMonth('updated_at', $month)
        ->get();

    foreach ($maturities as $mut) {
        $solfa += $mut->amount;
    }

    $salary = $basic_salary - $solfa;

    return response()->json([
        'basic_salary' => $basic_salary,
        'maturities' => $maturities,
        'total_solfa' => $solfa,
        'remaining_salary' => $salary
    ]);
}

function calculateTotalHours($hoursArray) {
    $totalHours = 0;
    foreach ($hoursArray as $hours) {
        $totalHours += $hours;
    }
    return $totalHours;
}
public function getteacherworkhour($teacher_id, $year, $month)
{
    // استرجاع برنامج الدوام الأسبوعي الثابت للمعلم
    $teacherSchedule = Teacher_Schedule::where('teacher_id', $teacher_id)->get();
    $teacher = Teacher::find($teacher_id);
    if (!$teacher) {
        return response()->json(['the teacher not found']);
    }

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
        $isWeekend = in_array($dayOfWeek, ['Friday', 'Saturday']);

        $dailyWorkingHours = 0; // لجمع ساعات العمل اليومية

        if (!$isHoliday && !$isWeekend) {
            // تكرار على جميع الفترات في اليوم الحالي
            foreach ($teacherSchedule as $schedule) {
                if ($schedule->day_of_week == $dayOfWeek) {
                    $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time);
                    $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time);
                    $workingHours = $endTime->diffInHours($startTime);

                    $dailyWorkingHours += $workingHours;
                }
            }
        }

        // إضافة تفاصيل اليوم إلى المصفوفة
        $attendanceDetails[] = [
            'working_hours' => $dailyWorkingHours,
        ];
    }

    // احتساب الساعات الإضافية
    $hour_added = $teacher->totalHoursAdded();
    $totalWorkingHours = $this->calculateTotalHours(array_column($attendanceDetails, 'working_hours')) + $hour_added;

    return $totalWorkingHours;
}


public function getTeacherExtraHours(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseError(['errors' => $validator->errors()]);
        }

        $user = User::find(auth()->user()->id);
        $teacher = $user->teacher()->first();

        if(!$teacher)
        {
            return response()->json(['the teacher not found']);
        }
        $month = $request->month;

        $totalHours = Hour_Added::getTeacherHoursForMonth($teacher->id, $month);
        $hoursDetails = Hour_Added::where('teacher_id', $teacher->id)
        ->whereMonth('created_at', $month)
        ->get();

        return response()->json([
            'teacher_id' => $teacher->id,
            'month' => $month,
            'total_hours' => $totalHours,
            'hours_details' => $hoursDetails,

        ]);
    }

    public function getTeacherOutOfWorkHour(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $teacher = $user->teacher()->first();

        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseError(['errors' => $validator->errors()]);
        }
        // $teacher = Teacher::find($teacher_id);
        if(!$teacher)
        {
            return response()->json(['the teacher not found']);
        }
        $month = $request->month;

        $totalHours = Out_Of_Work_Employee::totalHoursOutOfWork($teacher->id, $month);
        $hoursDetails = Out_Of_Work_Employee::where('teacher_id', $teacher->id)
        ->whereMonth('date', $month)
        ->get();

        return response()->json([
            'teacher_id' => $teacher->id,
            'month' => $month,
            'total_hours' => $totalHours,
            'hours_details' => $hoursDetails,

        ]);
    }

    public function display_all_my_course()
    {
        $user = User::find(auth()->user()->id);
        $teacher = $user->teacher()->first();

        if(!$teacher)
        {
            return response()->json(['the teacher not found']);
        }

        $courses = course::where('teacher_id',$teacher->id)->get();

        return $courses;

    }

    // public function info_course($id_course)
    // {
    //     $course = Course::where('id', $id_course)->with('subject')->with('classs')->get();
    //     return $course;
    // }

    public function display_info_course($course_id)
{
    // route::get('display_info_course/{course_id}', [AdminZaController::class, 'display_info_course']);

    $course = Course::where('id', $course_id)
        ->with('publish.image')
        ->first();

    if (!$course) {
        return response()->json([
            'status' => 'false',
            'message' => 'Course not found'
        ]);
    }

    // عدد الطلاب المسجلين في الدورة
    $num_order_for_course = Order::where('course_id', $course_id)->where('student_type','11')->count();
 // return $num_order_for_course;
    // المبلغ الذي جمعه المعهد من الطلاب المسجلين
    $Money = $num_order_for_course * $course->cost_course;

    // مصاريف الدورة الكلية
    $expenses = Expenses::where('course_id', $course_id)->sum('total_cost') ?? 0;

    $Money_without_expenses = $Money - $expenses;
    $my_money_from_course= ($Money_without_expenses * $course->percent_teacher)/100;
    // النسبة التي يحصل عليها المعهد بعد خصم نسبة المدرس
    $institute_percentage = 100 - $course->percent_teacher;

    // المبلغ الذي يجب جمعه ليغطي المصاريف ويحقق الربح المطلوب
    $required_money_to_open = $expenses + $course->Minimum_win;  // إضافة الربح المطلوب 500000 إلى المصاريف

    // المبلغ الذي يجب جمعه من الطلاب ليغطي المطلوب بعد خصم نسبة المدرس
    $required_total_money = $required_money_to_open / ($institute_percentage / 100);

    // حساب عدد الطلاب اللازمين لجمع هذا المبلغ
    $num_students_required = ceil($required_total_money / $course->cost_course);

    // حساب عدد الطلاب المتبقيين لتغطية التكاليف
    if ($num_order_for_course > $num_students_required) {
        $num_students_remaining = 0;
    }
    else {
        $num_students_remaining = $num_students_required - $num_order_for_course;
    }


    // تغيير حالة الدورة إذا كانت الشروط مستوفاة
    if ($Money >= $required_money_to_open) {
        $course->Course_status = 1;
        $course->save();
    }

    return response()->json([
        'status' => 'true',
        'course' => $course,
        'num_students_registered_in_course' => $num_order_for_course,
        'my_money_from_course' => $my_money_from_course,
        // 'total_money_collected' => $Money,
        // 'total_expenses' => $expenses,
        'num_students_required_shoud' => $num_students_required,
        'num_students_remaining' => $num_students_remaining
    ]);
}

//عرض طلبات الدورة
public function display_student_course($course_id)
{
    $order = Order::where('course_id', $course_id)->where('student_type','11')->with('student.user')->get();

    return $order;
}

}
