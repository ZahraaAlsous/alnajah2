<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Actions_log; // استدعاء موديل ActionLog لتسجيل الأنشطة
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Teacher_subject;
use Illuminate\Support\Facades\DB;


class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (Auth::check()) {
        //     $user = Auth::user();

        //     // تسجيل النشاط لكل المستخدمين
        //     Actions_log::create([
        //         'user_id' => $user->id,  // ID الخاص بالمستخدم
        //         'action' => $request->method() . ' ' . $request->path(), // نوع العملية والمسار
        //         'description' => 'قام المستخدم بطلب ' . $request->path(), // وصف للعملية
        //     ]);
        // }

        // return $next($request);

        if (Auth::check()) {
            $user = Auth::user();

            // إنشاء وصف مخصص بناءً على الـ path
            $description = $this->getActionDescription($request->path(), $request);

            // تسجيل النشاط
            Actions_log::create([
                'user_id' => $user->id,
                'action' => $request->method() . ' ' . $request->path(),
                'description' => $description,
            ]);
        }

        return $next($request);
    }

    /**
     * الحصول على الوصف بناءً على الـ path المطلوب
     */
    // private function getActionDescription($path)
    // {

    //     if (preg_match('/^api\/monetor\/update_profile_student\/\d+$/', $path)) {
    //         return 'تم تحديث معلومات طالب';
    //     }

    //     // أضف منطقًا مخصصًا لكل API هنا
    //     switch ($path) {
    //         // case 'api/monetor/desplay_publish':
    //         //     return 'عرض المنشورات من قبل المستخدم';
    //         // case 'api/monetor/create_publish':
    //         //     return 'إنشاء منشور جديد';
    //         // case 'api/monetor/update_publish':
    //         //     return 'تحديث منشور قائم';
    //         // // أضف المزيد من الحالات حسب الحاجة...
    //         // default:
    //         //     return 'قام المستخدم بطلب ' . $path;

    //         case 'api/monetor/update_profile_student':
    //             return 'تم تحديث معلومات طالب';
    //         default:
    //             return 'قام المستخدم بطلب ' . $path;
    //     }
    // }

//     private function getActionDescription($path)
// {
//     // تحديث معلومات طالب
//     if (preg_match('/^api\/monetor\/update_profile_student\/(\d+)$/', $path, $matches)) {
//         // استخراج معرف الطالب من الـ path
//         $studentId = $matches[1];

//         // جلب بيانات الطالب عبر العلاقة بين User و Student
//         $student = \App\Models\Student::find($studentId);

//         // إذا كان الطالب موجودًا، جلب المستخدم المرتبط بالطالب وعرض اسمه
//         if ($student && $student->user) {
//             $user = $student->user;
//             return 'تم تحديث معلومات الطالب: ' . $user->first_name . ' ' . $user->last_name;
//         } else {
//             return 'تم تحديث معلومات طالب غير معروف (ID: ' . $studentId . ')';
//         }
//     }



//     // التعامل مع مسارات أخرى
//     switch ($path) {
//         case 'api/monetor/desplay_publish':
//             return 'عرض المنشورات من قبل المستخدم';
//         // أضف المزيد من الحالات حسب الحاجة...
//         default:
//             return 'قام المستخدم بطلب ' . $path;
//     }
// }


private function getActionDescription($path, $request)
{
    //تم تجريبه
    // تحديث معلومات طالب
    if (preg_match('/^api\/monetor\/update_profile_student\/(\d+)$/', $path, $matches)) {
        // استخراج معرف الطالب من الـ path
        $studentId = $matches[1];

        // جلب بيانات الطالب عبر العلاقة بين User و Student
        $student = \App\Models\Student::find($studentId);
        $data = $request->input();
        // إذا كان الطالب موجودًا، جلب المستخدم المرتبط بالطالب وعرض اسمه
        if ($student && $student->user) {
            $user = $student->user;
            $newClass = \App\Models\Classs::find($data['class_id']);
            $newSection = \App\Models\Section::find($data['section_id']);
            return 'تم تحديث معلومات الطالب: ' . $user->first_name .' ' .$user->father_name.  ' ' . $user->last_name . ' ' . $user->mother_name . ' ' .$user->phone . ' ' . $user->address . ' ' . $user->school_tuition. ' '.$student->classs->name .' '.$student->section->num_section   ."\n".' ((إلى)) '."\n" . $data['first_name'] . ' ' . $data['father_name'] . ' ' . $data['last_name'] . ' ' . $data['mother_name'] . ' ' . $data['phone'] . ' ' . $data['address'] . ' ' . $data['school_tuition'] . ' ' . ($newClass ? $newClass->name : 'غير معروف') . ' ' . ($newSection ? $newSection->num_section : 'غير معروف');
        } else {
            return 'تم تحديث معلومات طالب غير معروف (ID: ' . $studentId . ')';
        }
    }

    //تم تجريبه
    // إرسال إنذار أو ثناء ...لطالب
    // if (preg_match('/^api\/monetor\/create_note\/(\d+)$/', $path, $matches)) {
    //     $studentId = $matches[1];
    //     $student = \App\Models\Student::find($studentId);
    //     return $student ? 'تم إرسال ملاحظة /إنذار للطالب: ' . $student->user->first_name . ' ' . $student->user->last_name : 'تم إرسال ملاحظة لطالب غير معروف';
    // }
    if (preg_match('/^api\/monetor\/create_note\/(\d+)$/', $path, $matches)) {
        $studentId = $matches[1];
        $student = \App\Models\Student::find($studentId);
    
        // التحقق من وجود الطالب
        if (!$student) {
            return 'تم إرسال ملاحظة لطالب غير معروف';
        }
    
        // الحصول على البيانات المرسلة
        $data = $request->input(); // الحصول على البيانات المرسلة عبر الـ API
    
        // عرض التفاصيل المخزنة
        $details = '';
        if (isset($data['note_details']) && is_array($data['note_details'])) {
            foreach ($data['note_details'] as $detail) {
                $details .= ' [' . $detail['key'] . ': ' . $detail['value'] . ']';
            }
        }
    
        // عرض النتيجة النهائية
        return 'تم إرسال ' . ($data['type'] ?? 'ملاحظة') . ' للطالب: ' 
            . $student->user->first_name . ' ' . $student->user->last_name ."\n"
            . ' بالتفاصيل التالية: ' . ($data['text'] ?? ' ');
    }

    //تم تجربته
    //تعديل أو إضافة برنامج مدرس
    if (preg_match('/^api\/monetor\/update_Weekly_Schedule_for_student\/(\d+)$/', $path, $matches)) {
        $teacherId = $matches[1];
        $teacher = \App\Models\Teacher::find($teacherId);
        return $teacher ? 'تم تعديل برنامج دوام المدرس: ' . $teacher->user->first_name . ' ' . $teacher->user->last_name : 'تم تعديل برنامج لأستاذ غير معروف';
    }

    //تم تجربته
    //إضافة إعلان
    if (preg_match('/^api\/monetor\/add_publish$/', $path)) {
        $data = $request->input();
        if ($request->path) {
            return 'تم إضافة إعلان جديد و هو ' . $data['description'] . ' مع إرفاق صورة للإعلان ';
        }
        return 'تم إضافة إعلان جديد و هو ' . $data['description'];
    }

    //تم تجربته
    //حذف إعلان
    if (preg_match('/^api\/monetor\/delete_publish\/(\d+)$/', $path, $matches)) {
        $publishId = $matches[1];
        $publish = \App\Models\Publish::find($publishId);
        if ($publish->course_id == null) {
            return 'تم حذف الإعلان : ' . $publish->description;
        }
        $course = \App\Models\Course::find($publish->course_id);

        return 'تم حذف الإعلان : ' . $publish->description . ' للدورة '. $course->name_course;
    }
        // }
        

    //تم تجربته   
    //تعديل إعلان
    if (preg_match('/^api\/monetor\/update_publish\/(\d+)$/', $path, $matches)) {
        $publishId = $matches[1];
        $publish = \App\Models\Publish::find($publishId);
        $data = $request->input();
        if ($publish->course_id == null) {
            if ($request->path && $request->description) {
                return 'تم تعديل الإعلان : ' . $publish->description . ' إلى ' . $data['description'] . ' و قد تم تعديل صورة ';
            }
            if ($request->path) {
                return 'تم تعديل الإعلان : ' . $publish->description . ' حيث تم تعديل صورة ';
            }
            if ($request->description) {
                return 'تم تعديل الإعلان : ' . $publish->description . ' إلى ' . $data['description'];
            }

             
        }
        $course = \App\Models\Course::find($publish->course_id);

        if ($request->path && $request->description) {
            return 'تم تعديل الإعلان : ' . $publish->description . ' للدورة '. $course->name_course . ' إلى ' . $data['description'] . ' و قد تم تعديل صورة ';
        }

        if ($request->path) {
            return 'تم تعديل الإعلان : ' . $publish->description . ' للدورة '. $course->name_course . ' حيث تم تعديل صورة ';
        }
        if ($request->description) {
            return 'تم تعديل الإعلان : ' . $publish->description . ' للدورة '. $course->name_course . ' إلى ' . $data['description'];
        }
    }
    

    // if (preg_match('/^api\/monetor\/add_mark_to_student\/(\d+)$/', $path, $matches)) {
    //     $studentId = $matches[1];
    //     $student = \App\Models\Student::find($studentId);
    //     return $student ? 'تم إضافة علامة للطالب: ' . $student->user->first_name . ' ' . $student->user->last_name : 'تم إضافة علامة لطالب غير معروف';
    // }

    if (preg_match('/^api\/monetor\/edit_mark_for_student\/(\d+)\/subject\/(\d+)$/', $path, $matches)) {
        $studentId = $matches[1];
        $subjectId = $matches[2];
        $student = \App\Models\Student::find($studentId);
        return $student ? 'تم تعديل علامة الطالب: ' . $student->user->first_name . ' ' . $student->user->last_name . ' في المادة رقم: ' . $subjectId : 'تم تعديل علامة لطالب غير معروف';
    }

    //تم تجريبه
    //إضافة غياب لطالب
    if (preg_match('/^api\/monetor\/add_student_out_of_work\/(\d+)$/', $path, $matches)) {
        $studentId = $matches[1];
        $student = \App\Models\Student::find($studentId);

        if ($request->justification) {
            return $student ? 'تم إضافة غياب للطالب: ' . $student->user->first_name . ' ' . $student->user->last_name . ' في اليوم ' . $request->date . ' و قد تم تبريره بالتالي ' . $request->justification : 'تم إضافة غياب لطالب غير معروف';
        }
        return $student ? 'تم إضافة غياب للطالب: ' . $student->user->first_name . ' ' . $student->user->last_name . ' في اليوم ' . $request->date : 'تم إضافة غياب لطالب غير معروف';
    }

    
    //تم تجريبه
    //تغيير حالة مناقشة مفعلة مغلقة
    if (preg_match('/^api\/monetor\/off_on_post\/(\d+)$/', $path, $matches)) {
        $postId = $matches[1];
        $post = \App\Models\Post::find($postId);
    
        if ($post) {
            // تحديد النص بناءً على قيمة state_on_off
            $stateText = $post->state_on_off == 1 ? 'مغلقة' : 'مفتوحة';
            return 'تم تغيير حالة المناقشة: ' . $post->quostion . ' إلى ' . $stateText;
        } else {
            return 'تم تغيير حالة المناقشة رقم: ' . $postId;
        }
    }

    //تم تجريبه
    //رفع برنامج لشعبة محددة لصف معين
    if (preg_match('/^api\/monetor\/upload_program_section\/(\d+)$/', $path, $matches)) {
        $sectionId = $matches[1];
        $section = \App\Models\Section::find($sectionId);
        return ' تم رفع برنامج للشعبة : ' . $section->num_section . ' الصف ' . $section->classs->name;
    }

    //تم تجريبه
    //حذف برنامج لشعبة محددة لصف معين    
    if (preg_match('/^api\/monetor\/delete_program_section\/(\d+)$/', $path, $matches)) {
        $programId = $matches[1];
        $program = \App\Models\Program_Student::find($programId);
        return 'تم حذف برنامج الشعبة ' . $program->section->num_section . ' الصف ' . $program->section->classs->name;
    }

    //تم تجريبه
    //تعديل برنامج شعبة محددة لصف معين
    if (preg_match('/^api\/monetor\/update_program_section\/(\d+)$/', $path, $matches)) {
        $programId = $matches[1];
        $program = \App\Models\Program_Student::find($programId);
        return 'تم تعديل برنامج الشعبة ' . $program->section->num_section . ' الصف ' . $program->section->classs->name;
    }

    //تم تجريبه
    //تعديل معلومات الموظف
    if (preg_match('/^api\/monetor\/update_profile_employee\/(\d+)$/', $path, $matches)) {
        $employeeId = $matches[1];
        $employee = \App\Models\Employee::find($employeeId);
        $data = $request->input();
        return $employee ? 'تم تعديل معلومات الموظف ' . $employee->first_name . ' ' . $employee->last_name . ' ' .$employee->phone . ' ' . $employee->address . ' ' . $employee->salary . ' ' . $employee->type . '\n ((إلى)) \n' . $data['first_name'] . ' ' . 
        $data['last_name'] . ' ' . $data['phone'] . ' ' . $data['address'] . ' ' . $data['salary'] . ' ' . $data['type'] : 'تم تعديل معلومات موظف غير معروف';
    
    }

    //تم تجربته
    //تسجيل طالب خارجي في كورس
    if (preg_match('/^api\/monetor\/add-order-course\/(\d+)$/', $path, $matches)) {
        $data = $request->input();

        $courseId = $matches[1];
    
        $course = \App\Models\Course::find($courseId);

        if ($course) {
            return 'تم تسجيل الطالب ' .$data['first_name'] . ' '.$data['last_name'] .' ابن '. $data['father_name'] . ' في دورة '. $course->name_course;
        } else {
            return 'تم تسجيل طالب في دورة غير معروفة (ID: ' . $courseId . ')';
        }
    }
    

    // if (preg_match('/^api\/monetor\/update_teacher_profile\/(\d+)$/', $path, $matches)) {
    //     $teacherId = $matches[1];
    //     $teacher = \App\Models\Teacher::find($teacherId);
    //     return $teacher ? 'تم تعديل معلومات المدرس: ' . $teacher->user->first_name . ' ' . $teacher->user->last_name : 'تم تعديل معلومات مدرس غير معروف';
    // }

    //تم تجربته
    //رفع مرفق لدورة محددة
    if (preg_match('/^api\/monetor\/upload_file_image_for_course\/(\d+)$/', $path, $matches)) {
        $courseId = $matches[1];
    
        $course = \App\Models\Course::find($courseId);
    
        if ($course) {
            if ($request->description) {
                return 'تم رفع مرفق للدورة: ' . $course->name_course . ' بالوصف التالي ' . $request->description;
            }
            else {
                return 'تم رفع مرفق للدورة: ' . $course->name_course . ' دون وصف ';
            }
        } else {
            return 'تم رفع مرفق لدورة غير معروفة (ID: ' . $courseId . ')';
        }
    }

    //تم تجربته
    //رفع مرفق لعام دراسي الحالي
    if (preg_match('/^api\/monetor\/upload_file_image\/(\d+)$/', $path, $matches)) {
        $academy = \App\Models\Academy::find('1');
        $subjectId = $matches[1];
    
        $subject = \App\Models\Subject::find($subjectId);
    
        if ($subject) {
                return 'تم رفع مرفق لمادة: ' . $subject->name . ' للصف ' . $subject->classs->name .' للعام الدراسي الحالي '.$academy->year . ' بالوصف التالي ' . $request->description;
        } else {
            return 'تم رفع مرفق للعام الدراسي الحالي غير معروفة (ID: ' . $courseId . ')';
        }
    }

    //تم تجربته
    //رفع مرفق لعام دراسي محدد أي أرشيف
    if (preg_match('/^api\/monetor\/upload_file_image_archive\/(\d+)$/', $path, $matches)) {
        // استخراج معرف الأرشيف من الـ path
        $archiveId = $matches[1];
    
        // جلب بيانات الأرشيف عبر معرف الأرشيف
        $archive = \App\Models\Archive::find($archiveId);
    
        // إذا كان الأرشيف موجودًا، يمكن عرض تفاصيله أو عرض رسالة عند عدم وجود الأرشيف
        if ($archive) {
            return 'تم رفع مرفق للأرشيف : ' . $archive->year . ' للمادة: ' . $archive->subject->name . ' للصف: ' . $archive->class->name . ' بالوصف التالي ' . $request->description;
        } else {
            return 'تم رفع مرفق لأرشيف غير معروف (ID: ' . $archiveId . ')';
        }
    }
    

    //تم تجربته
    //الموافقة على طلب التسجيل لطالب في كورس
    if (preg_match('/^api\/monetor\/ok_order_course\/(\d+)$/', $path, $matches)) {
        $orderId = $matches[1];
    
        $order = \App\Models\Order::find($orderId);
    
        if ($order) {
            return 'تمت الموافقة على طلب التسجيل للطالب: ' . $order->student->user->first_name . ' ' . $order->student->user->last_name . ' في الدورة ' . $order->course->name_course;
        } else {
            return 'تمت الموافقة على طلب التسجيل غير معروف (ID: ' . $orderId . ')';
        }
    }
    
    //تم تجربته
    //تم رفض طلب التسجيل لطالب في كورس
    if (preg_match('/^api\/monetor\/no_order_course\/(\d+)$/', $path, $matches)) {
        $orderId = $matches[1];
    
        $order = \App\Models\Order::find($orderId);
    
        if ($order) {
            return 'تمت رفض طلب التسجيل للطالب: ' . $order->student->user->first_name . ' ' . $order->student->user->last_name . ' في الدورة ' . $order->course->name_course;
        } else {
            return 'تمت رفض طلب التسجيل غير معروف (ID: ' . $orderId . ')';
        }
    }

    //تم تجربته
    //حذف ملف لمادة 
    if (preg_match('/^api\/monetor\/delete_file_image\/(\d+)\/(.+)$/', $path, $matches)) {
        $fileImgId = $matches[1];
    
        $file = \App\Models\File_Archive::find($fileImgId);
    
        if ($file) {
                return 'تم حذف المرفق: ' . $file->description . ' للمادة ' . $file->archive->subject->name . ' الصف ' . $file->archive->class->name . ' من العام الدراسي ' . $file->archive->year;
           
        } else {
            return 'تم حذف مرفق غير معروف (ID: ' . $fileImgId . ')';
        }
    }

    // if (preg_match('/^api\/monetor\/update-file-image\/(\d+)$/', $path, $matches)) {
    //     $fileImgId = $matches[1];
    
    //     $file = \App\Models\File_Archive::find($fileImgId);
    
    //     if ($file) {
    //         $data = $request->all();

    //         if ($request->description) {
    //             return 'تم تحديث المرفق: ' . $file->description . ' (  ' . ', الوصف السابق: ' . $file->description  . ', الوصف الجديد: ' . $data['description'] . ') للمادة ' . $file->archive->subject->name . ' الصف ' . $file->archive->class->name . ' من العام الدراسي ' . $file->archive->year;
    //         }
    
    //         return 'تم تحديث المرفق: ' . $file->description  . ') للمادة ' . $file->archive->subject->name . ' الصف ' . $file->archive->class->name . ' من العام الدراسي ' . $file->archive->year;
    //     } else {
    //         return 'تم تحديث مرفق غير معروف (ID: ' . $fileImgId . ')';
    //     }
    // }

    //تم تجريبه
    //حذف تعليق لطالب
    if (preg_match('/^api\/monetor\/delete_comment\/(\d+)$/', $path, $matches)) {
        $commentId = $matches[1];
    
        // جلب بيانات التعليق عبر معرف التعليق
        $comment = \App\Models\Comment::find($commentId);
    
        if ($comment) {
            // عرض تفاصيل التعليق المحذوف
            return 'تم حذف التعليق: "' . $comment->description . '" الذي كتبه ' . $comment->student->user->first_name . ' ' . $comment->student->user->last_name . ' في المناقشة ذات العنوان التالي ' . $comment->post->quostion;
        } else {
            return 'تم حذف تعليق غير معروف (ID: ' . $commentId . ')';
        }
    }

    //تم تجريبه
    //تعديل تبرير غياب طالب
    if (preg_match('/^api\/monetor\/updateAbsence_for_student\/(\d+)\/(.+)$/', $path, $matches)) {
        $studentId = $matches[1];
        $date = $matches[2];
    
        $student = \App\Models\Student::find($studentId);
        $absence = \App\Models\Out_Of_Work_Student::where('student_id', $studentId)->where('date', $date)->first();
        return 'تم تعديل تبرير غياب الطالب: ' . $student->user->first_name . ' ' . $student->user->last_name . ' من التبرير السابق: "' . $absence->justification . '" إلى التبرير الجديد: "' . $request->justification. '" للتاريخ: ' . $date;
    }
    
    //تم تجريبه
    //حذف تبرير غياب طالب
    if (preg_match('/^api\/monetor\/delete_student_out_of_work\/(\d+)\/(.+)$/', $path, $matches)) {
        $studentId = $matches[1];
        $date = $matches[2];
    
        $student = \App\Models\Student::find($studentId);
        $absence = \App\Models\Out_Of_Work_Student::where('student_id', $studentId)->where('date', $date)->first();
        return 'تم حذف سجل غياب الطالب: ' . $student->user->first_name . ' ' . $student->user->last_name . ' للتاريخ: ' . $date . ' مع التبرير: "' . $absence->justification . '"' ?? 'تم حذف سجل غياب لطالب غير معروف (ID: ' . $studentId . ')';
    }

    //تم تجربته
    //إضافة ساعات إضافية للأستاذ
    if (preg_match('/^api\/monetor\/add_extrahour\/(\d+)$/', $path, $matches)) {
        $teacherId = $matches[1]; 
        
        $teacher = \App\Models\Teacher::find($teacherId);

        if ($request->note_hour_added) {
            return 'تم إضافة '.$request->num_hour_added. ' ساعات إضافية تحت ملاحظة '. $request->note_hour_added. ' للأستاذ ' . $teacher->user->first_name . ' '. $teacher->user->last_name;
        }
        return 'تم إضافة '.$request->num_hour_added. ' ساعات إضافية ' . ' للأستاذ ' . $teacher->user->first_name . ' '. $teacher->user->last_name;

    }

    //تم تجربته
    //حذف ساعات إضافية للأستاذ
    if (preg_match('/^api\/monetor\/delete_extrahour\/(\d+)\/(\d+)$/', $path, $matches)) {
        $teacherId = $matches[1];
        $hourId = $matches[2]; 
        
        // جلب بيانات الأستاذ
        $teacher = \App\Models\Teacher::find($teacherId);
        $extraHour = \App\Models\Hour_Added::find($hourId);  // هنا يتم جلب الساعات الإضافية
    
        if (!$teacher || !$teacher->user) {
            return 'تم حذف ساعات إضافية لأستاذ غير معروف (ID: ' . $teacherId . ')';
        }
    
        if ($extraHour) {
            if ($extraHour->note_hour_added) {
                return 'تم حذف ' . $extraHour->num_hour_added . ' ساعات إضافية كانت تحت ملاحظة "' . $extraHour->note_hour_added . '" للأستاذ ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
            }
            return 'تم حذف ' . $extraHour->num_hour_added . ' ساعات إضافية '. ' للأستاذ ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
        } else {
            return 'تم حذف ساعات إضافية غير معروفة (ID: ' . $hourId . ') للأستاذ ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
        }
    }

    //تم تجربته
    //حذف ساعات إضافية للأستاذ
    if (preg_match('/^api\/monetor\/update_extrahour\/(\d+)\/(\d+)$/', $path, $matches)) {
        $teacherId = $matches[1];
        $hourId = $matches[2]; 
        
        // جلب بيانات الأستاذ
        $teacher = \App\Models\Teacher::find($teacherId);
        $extraHour = \App\Models\Hour_Added::find($hourId);  // هنا يتم جلب الساعات الإضافية
    
        if (!$teacher || !$teacher->user) {
            return 'تم حذف ساعات إضافية لأستاذ غير معروف (ID: ' . $teacherId . ')';
        }

        if ($extraHour) {
            if ($request->num_hour_added && $request->note_hour_added) {
                return 'تم تعديل عدد الساعات الإضاية من  ' . $extraHour->num_hour_added .' إلى '. $request->num_hour_added .' وقد تم تعديل ملاحظتها حيث كانت  "' . $extraHour->note_hour_added . '" إلى "'. $request->note_hour_added. '" للأستاذ ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
            }

            if ($request->num_hour_added) {
                return 'تم تعديل عدد الساعات الإضاية من  ' . $extraHour->num_hour_added .' إلى '. $request->num_hour_added .' و التي تندرج تحت ملاحظة  "' . $extraHour->note_hour_added . '" للأستاذ ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
            }
            return 'تم تعديل ملاحظة الساعات الإضافية ال  ' . $extraHour->num_hour_added .' من "'. $extraHour->note_hour_added .'" إلى "' . $request->note_hour_added . '" للأستاذ ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
        
        } else {
            return 'تم حذف ساعات إضافية غير معروفة (ID: ' . $hourId . ') للأستاذ ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
        }
    
        // if ($extraHour) {
        //     if ($extraHour->note_hour_added) {
        //         return 'تم تعديل ' . $extraHour->num_hour_added . ' ساعات إضافية كانت تحت ملاحظة "' . $extraHour->note_hour_added . '" للأستاذ ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
        //     }
        //     return 'تم حذف ' . $extraHour->num_hour_added . ' ساعات إضافية '. ' للأستاذ ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
        // } else {
        //     return 'تم حذف ساعات إضافية غير معروفة (ID: ' . $hourId . ') للأستاذ ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
        // }
    }

    //تم تجريبه
    //تعديل معلومات الأستاذ
    if (preg_match('/^api\/monetor\/update_teacher_profile\/(\d+)$/', $path, $matches)) {
        $teacherId = $matches[1];
    
        // جلب بيانات المدرس قبل التعديل
        $teacher = \App\Models\Teacher::find($teacherId);
    
        if (!$teacher || !$teacher->user) {
            return 'تم تعديل معلومات مدرس غير معروف (ID: ' . $teacherId . ')';
        }
        
        $oldData = $teacher->toArray();
    
        $newData = $request->all();
    
        $subject = $teacher->subject->first();
     $message = 'تم تعديل معلومات المدرس: ' . ($teacher->user->first_name ?? 'غير معروف') . ' ' . ($teacher->user->last_name ?? 'غير معروف') . "\n";
    
     $message .= 'المعلومات القديمة: سعر ساعته: ' . ($oldData['cost_hour'] ?? 'غير معروف') . ', شهادته: ' . ($oldData['certificate'] ?? 'غير معروف') . ', الصف: ' . ($teacher->classs->name ?? 'غير معروف') . ', المادة: ' . ($subject->name ?? 'غير معروف')  . "\n";
    
     $message .= 'المعلومات الجديدة: سعر ساعته: ' . ($newData['cost_hour'] ?? 'غير معروف') . ', شهادته: ' . ($newData['certificate'] ?? 'غير معروف') . ', الصف: ' . ($newData['classs_id'] ?? 'غير معروف') . ', المادة: ' . ($newData['name_subject'] ?? 'غير معروف')  . "\n";
    
     return $message;
    }

    //تم تجربته
    //رفع علامات طالب
    if (preg_match('/^api\/monetor\/add_mark_to_student\/(\d+)$/', $path, $matches)) {
        $studentId = $matches[1];
    
        $student = \App\Models\Student::find($studentId);
    
        if (!$student || !$student->user) {
            return 'تم إضافة علامة لطالب غير معروف (ID: ' . $studentId . ')';
        }
    
        $marksData = $request->all();
    
        $oldMarks = \App\Models\Mark::where('student_id', $studentId)
                        ->where('subject_id', $marksData['subject_id'])
                        ->first();
    
        $message = 'تم إضافة درجات جديدة للطالب: ' . $student->user->first_name . ' ' . $student->user->last_name . "\n";
    
        if ($oldMarks) {
            $message .= 'الدرجات القديمة: ' .
                ' بونس: ' . ($oldMarks->ponus ?? ' - ').
                ', الوظيفة: ' . ($oldMarks->homework ?? ' - ').
                ', الشفهي: ' . ($oldMarks->oral ?? ' - ').
                ', اختبار1: ' . ($oldMarks->test1 ?? ' - ').
                ', اختبار2: ' . ($oldMarks->test2 ?? ' - ').
                ', امتحان نصف السنة: ' . ($oldMarks->exam_med ?? ' - ').
                ', الامتحان النهائي: ' . ($oldMarks->exam_final ?? ' - '). "\n";
        } else {
            $message .= 'لا توجد درجات سابقة لهذه المادة.' . "\n";
        }
    
        $message .= 'الدرجات الجديدة: ' .
            ' بونس: ' . ($marksData['ponus'] ?? $oldMarks->ponus ?? ' - ').
            ', الوظيفة: ' . ($marksData['homework'] ?? $oldMarks->homework ?? ' - ').
            ', الشفهي: ' . ($marksData['oral'] ?? $oldMarks->oral ?? ' - ').
            ', اختبار1: ' . ($marksData['test1'] ?? $oldMarks->test1 ?? ' - ').
            ', اختبار2: ' . ($marksData['test2'] ?? $oldMarks->test2 ?? ' - ').
            ', امتحان نصف السنة: ' . ($marksData['exam_med'] ?? $oldMarks->exam_med ?? ' - ').
            ', الامتحان النهائي: ' . ($marksData['exam_final'] ?? $oldMarks->exam_final ?? ' - ');
    
        return $message;
    }

    //تم تجربته
    //حذف ملف دورة
    if (preg_match('/^api\/monetor\/delete_file_course\/(\d+)$/', $path, $matches)) {
        $fileId = $matches[1];
    
        $file = \App\Models\File_course::find($fileId);
    
        if ($file) {
            return 'تم حذف الملف الذي وصفه: "' . $file->description . '" الخاص بالدورة "' . $file->course->name_course . '"';
        } else {
            return 'تم حذف ملف غير معروف (ID: ' . $fileId . ')';
        }
    }


    //تم تجربته
    //إضافة غياب للأستاذ أو الموظف
    if (preg_match('/^api\/monetor\/add_teachers_and_employee_absence$/', $path)) {
        if ($request->teacher_id != null) {
            $teacher = \App\Models\Teacher::where('id', $request->teacher_id)->first();
            $message = 'تم إضافة يوم غياب للأستاذ : '. $teacher->user->first_name.' '. $teacher->user->last_name."\n";
        }

        if ($request->employee_id != null) {
            $employee = \App\Models\Employee::where('id', $request->employee_id)->first();
            $message = 'تم إضافة يوم غياب ل : '.$employee->type . ' ' . $employee->first_name.' '. $employee->last_name."\n";
        }

        $message .= ' بتاريخ: ' . $request->date . ' لعدد ساعات: ' . $request->num_hour_out;
        return $message;
        

    
        // if ($teacher) {
        //     $message .= 'للمعلم: ' . $teacher->user->first_name . ' ' . $teacher->user->last_name;
        // } elseif ($employee) {
        //     $message .= 'للموظف: ' . $employee->user->first_name . ' ' . $employee->user->last_name;
        // }
    
        // $message .= ' بتاريخ: ' . $request->date . ' لعدد ساعات: ' . $request->num_hour_out;
    
        // return $message;
    }

    //تم تجربته
    //حذف غياب أستاذ
    if (preg_match('/^api\/monetor\/delete_absence_for_teacher\/(\d+)\/(.+)$/', $path, $matches)) {
        $teacherId = $matches[1];
        $absenceId = $matches[2];
    
        $absence = \App\Models\Out_Of_Work_Employee::where('id', $absenceId)->first();
        $teacher = \App\Models\teacher::where('id', $teacherId)->first();

        $message = 'تم حذف سجل غياب الأستاذ: ' . $teacher->user->first_name . ' ' . $teacher->user->last_name . "\n";
        $message .= ' للتاريخ: ' . $absence->date . "\n";
        $message .= ' و حيث كان يحمل الملاحظة : "' . ($absence->note ?? ' لا يوجد ملاحظة') . '"';
        return  $message ?? 'تم حذف سجل غياب لأستاذ غير معروف (ID: ' . $absenceId . ')';
    }
    
    //تم تجربته
    //تبرير أو تعديل تبرير غياب أستاذ
    if (preg_match('/^api\/monetor\/updatenoteforabsence_for_teacher\/(\d+)\/(.+)$/', $path, $matches)) {
        $teacherId = $matches[1];
        $absenceId = $matches[2];
    
        $absence = \App\Models\Out_Of_Work_Employee::where('id', $absenceId)->first();
        $teacher = \App\Models\teacher::where('id', $teacherId)->first();

        $message = 'تم تعديل/إضافة تبرير غياب الأستاذ: ' . $teacher->user->first_name . ' ' . $teacher->user->last_name . "\n";
        $message .= ' للتاريخ: ' . $absence->date . "\n";
        $message .= ' و حيث كان يحمل التبرير : "' . ($absence->note ?? ' لا يوجد تبرير') . '"'. "\n";
        $message .= 'التبرير الحديث :' .' "'. $request->note .'" ';
        return  $message ?? 'تم حذف سجل غياب لأستاذ غير معروف (ID: ' . $absenceId . ')';
    }




    /************teacher************/
    //تم تجربته
    if (preg_match('/^api\/teacher\/off_on_post\/(\d+)$/', $path, $matches)) {
        $postId = $matches[1];
        $post = \App\Models\Post::find($postId);
    
        if ($post) {
            // تحديد النص بناءً على قيمة state_on_off
            $stateText = $post->state_on_off == 1 ? 'مغلقة' : 'مفتوحة';
            return 'تم تغيير حالة المناقشة: ' . $post->quostion . ' إلى ' . $stateText;
        } else {
            return 'تم تغيير حالة المناقشة رقم: ' . $postId;
        }
    }
    //تم تجربته
    //رفع مرفق لدورة محددة
    if (preg_match('/^api\/teacher\/upload_file_image_for_course\/(\d+)$/', $path, $matches)) {
        $courseId = $matches[1];
    
        $course = \App\Models\Course::find($courseId);
    
        if ($course) {
            if ($request->description) {
                return 'تم رفع مرفق للدورة: ' . $course->name_course . ' بالوصف التالي ' . $request->description;
            }
            else {
                return 'تم رفع مرفق للدورة: ' . $course->name_course . ' دون وصف ';
            }
        } else {
            return 'تم رفع مرفق لدورة غير معروفة (ID: ' . $courseId . ')';
        }
    }

    //تم تجربته
    //رفع مرفق لعام دراسي الحالي
    if (preg_match('/^api\/teacher\/upload_file_image\/(\d+)$/', $path, $matches)) {
        $academy = \App\Models\Academy::find('1');
        $subjectId = $matches[1];
    
        $subject = \App\Models\Subject::find($subjectId);
    
        if ($subject) {
                return 'تم رفع مرفق لمادة: ' . $subject->name . ' للصف ' . $subject->classs->name .' للعام الدراسي الحالي '.$academy->year . ' بالوصف التالي ' . $request->description;
        } else {
            return 'تم رفع مرفق للعام الدراسي الحالي غير معروفة (ID: ' . $courseId . ')';
        }
    }

    //تم تجربته
    //رفع مرفق لعام دراسي محدد أي أرشيف
    if (preg_match('/^api\/teacher\/upload_file_image_archive\/(\d+)$/', $path, $matches)) {
        // استخراج معرف الأرشيف من الـ path
        $archiveId = $matches[1];
    
        // جلب بيانات الأرشيف عبر معرف الأرشيف
        $archive = \App\Models\Archive::find($archiveId);
    
        // إذا كان الأرشيف موجودًا، يمكن عرض تفاصيله أو عرض رسالة عند عدم وجود الأرشيف
        if ($archive) {
            return 'تم رفع مرفق للأرشيف : ' . $archive->year . ' للمادة: ' . $archive->subject->name . ' للصف: ' . $archive->class->name . ' بالوصف التالي ' . $request->description;
        } else {
            return 'تم رفع مرفق لأرشيف غير معروف (ID: ' . $archiveId . ')';
        }
    }


    //تم تجربته
    //حذف ملف لمادة 
    if (preg_match('/^api\/teacher\/delete_file_image\/(\d+)\/(.+)$/', $path, $matches)) {
        $fileImgId = $matches[1];
    
        $file = \App\Models\File_Archive::find($fileImgId);
    
        if ($file) {
                return 'تم حذف المرفق: ' . $file->description . ' للمادة ' . $file->archive->subject->name . ' الصف ' . $file->archive->class->name . ' من العام الدراسي ' . $file->archive->year;
           
        } else {
            return 'تم حذف مرفق غير معروف (ID: ' . $fileImgId . ')';
        }
    }


    if (preg_match('/^api\/teacher\/create_post\/(\d+)$/', $path, $matches)) {
        $sectionId = $matches[1];  // استخراج معرف الشعبة
    
        // جلب بيانات الشعبة
        $section = \App\Models\Section::find($sectionId);
    
        if (!$section) {
            return 'تم إنشاء منشور لشعبة غير معروفة (ID: ' . $sectionId . ')';
        }
    
        // جلب بيانات المدرس عبر الـ auth
        $teacher = \App\Models\Teacher::where('user_id', auth()->user()->id)->first();
        if (!$teacher) {
            return 'لم يتم العثور على المدرس المرتبط بحساب المستخدم.';
        }
    
        // جلب المادة التي يدرسها المدرس
        $subject = DB::table('teacher_subjects')->where('teacher_id', $teacher->id)->first();
        if (!$subject) {
            return 'لم يتم العثور على المادة المرتبطة بالمدرس.';
        }
    
        // جلب البيانات المدخلة من الـ request
        $postData = $request->all();
    
        // تكوين رسالة لتسجيل النشاط
        $message = 'تم إنشاء مناقشة جديدة بواسطة الأستاذ: ' . $teacher->user->first_name . ' ' . $teacher->user->last_name ."\n".
                   ' بعنوان المناقشة: "' . $post->quostion ."\n". '" للصف: ' . $section->classs->name .
                   ' الشعبة: ' . $section->num_section;
    
        return $message;
    }

    if (preg_match('/^api\/teacher\/edit_some_info_teacher_profile$/', $path, $matches)) {

        // جلب بيانات الطالب عبر العلاقة بين User و Student
        $user = \App\Models\User::find(auth()->user()->id);
        $data = $request->input();
        // إذا كان الطالب موجودًا، جلب المستخدم المرتبط بالطالب وعرض اسمه
        if ($user && $user->teacher) {
            $message = 'تم تحديث الأستاذ: ' . $user->first_name .' ' .$user->father_name.  ' ' . $user->last_name ."\n";
            $message .= 'المعلومات قبل التعديل: '.$user->phone . ' ' . $user->address."\n";
            $message .= 'المعلومات بعد التعديل: '.$data['phone'] . ' ' . $data['address'];
            return $message;
        } else {
            return 'تم تحديث معلومات أستاذ غير معروف (ID: ' . $user->id . ')';
        }
    }

    //تم تجريبه
    //حذف تعليق لطالب
    if (preg_match('/^api\/teacher\/delete_comment\/(\d+)$/', $path, $matches)) {
        $commentId = $matches[1];
    
        // جلب بيانات التعليق عبر معرف التعليق
        $comment = \App\Models\Comment::find($commentId);
    
        if ($comment) {
            // عرض تفاصيل التعليق المحذوف
            return 'تم حذف التعليق: "' . $comment->description . '" الذي كتبه ' . $comment->student->user->first_name . ' ' . $comment->student->user->last_name . ' في المناقشة ذات العنوان التالي ' . $comment->post->quostion;
        } else {
            return 'تم حذف تعليق غير معروف (ID: ' . $commentId . ')';
        }
    }

    //تم تجربته
    //رفع علامات طالب
    if (preg_match('/^api\/teacher\/add_mark_to_student\/(\d+)$/', $path, $matches)) {
        $studentId = $matches[1];
    
        $student = \App\Models\Student::find($studentId);
    
        if (!$student || !$student->user) {
            return 'تم إضافة علامة لطالب غير معروف (ID: ' . $studentId . ')';
        }
    
        $marksData = $request->all();
    
        $oldMarks = \App\Models\Mark::where('student_id', $studentId)
                        ->where('subject_id', $marksData['subject_id'])
                        ->first();
    
        $message = 'تم إضافة درجات جديدة للطالب: ' . $student->user->first_name . ' ' . $student->user->last_name . "\n";
    
        if ($oldMarks) {
            $message .= 'الدرجات القديمة: ' .
                ' بونس: ' . ($oldMarks->ponus ?? ' - ').
                ', الوظيفة: ' . ($oldMarks->homework ?? ' - ').
                ', الشفهي: ' . ($oldMarks->oral ?? ' - ').
                ', اختبار1: ' . ($oldMarks->test1 ?? ' - ').
                ', اختبار2: ' . ($oldMarks->test2 ?? ' - ').
                ', امتحان نصف السنة: ' . ($oldMarks->exam_med ?? ' - ').
                ', الامتحان النهائي: ' . ($oldMarks->exam_final ?? ' - '). "\n";
        } else {
            $message .= 'لا توجد درجات سابقة لهذه المادة.' . "\n";
        }
    
        $message .= 'الدرجات الجديدة: ' .
            ' بونس: ' . ($marksData['ponus'] ?? $oldMarks->ponus ?? ' - ').
            ', الوظيفة: ' . ($marksData['homework'] ?? $oldMarks->homework ?? ' - ').
            ', الشفهي: ' . ($marksData['oral'] ?? $oldMarks->oral ?? ' - ').
            ', اختبار1: ' . ($marksData['test1'] ?? $oldMarks->test1 ?? ' - ').
            ', اختبار2: ' . ($marksData['test2'] ?? $oldMarks->test2 ?? ' - ').
            ', امتحان نصف السنة: ' . ($marksData['exam_med'] ?? $oldMarks->exam_med ?? ' - ').
            ', الامتحان النهائي: ' . ($marksData['exam_final'] ?? $oldMarks->exam_final ?? ' - ');
    
        return $message;
    }

    //تم تجربته
    //حذف ملف دورة
    if (preg_match('/^api\/teacher\/delete_file_course\/(\d+)$/', $path, $matches)) {
        $fileId = $matches[1];
    
        $file = \App\Models\File_course::find($fileId);
    
        if ($file) {
            return 'تم حذف الملف الذي وصفه: "' . $file->description . '" الخاص بالدورة "' . $file->course->name_course . '"';
        } else {
            return 'تم حذف ملف غير معروف (ID: ' . $fileId . ')';
        }
    }

    

    if (preg_match('/^api\/teacher\/add_note_about_student\/(\d+)$/', $path, $matches)) {
        $studentId = $matches[1];  // استخراج معرف الشعبة
    
        // جلب بيانات الشعبة
        $student = \App\Models\Student::find($studentId);
    
        if (!$student) {
            return 'تم إنشاء ملاحظة لطالب غير معروفة (ID: ' . $studentId . ')';
        }
    
        // الحصول على البيانات المرسلة
        $data = $request->input(); // الحصول على البيانات المرسلة عبر الـ API
    
        // عرض التفاصيل المخزنة
        $details = '';
        if (isset($data['note_details']) && is_array($data['note_details'])) {
            foreach ($data['note_details'] as $detail) {
                $details .= ' [' . $detail['key'] . ': ' . $detail['value'] . ']';
            }
        }
    
        // عرض النتيجة النهائية
        return 'تم إرسال ' . ($data['type'] ?? 'ملاحظة') . ' للطالب: ' 
            . $student->user->first_name . ' ' . $student->user->last_name ."\n"
            . ' بالتفاصيل التالية: ' . ($data['text'] ?? ' ');
    }

    // if (preg_match('/^api\/teacher\/edit_mark\/(\d+)$/', $path, $matches)) {
    //     $markId = $matches[1];
    //     $mark = \App\Models\Mark::find($markId);
    //     return $student ? 'تم تعديل علامة الطالب: ' . $mark->student->user->first_name . ' ' . $mark->student->user->last_name ."\n". ' في مادة : ' . $mark->subject->name : 'تم تعديل علامة لطالب غير معروف';
    // }
    if (preg_match('/^api\/teacher\/edit_mark\/(\d+)$/', $path, $matches)) {
        $markId = $matches[1];
        $mark = \App\Models\Mark::find($markId);
    
        // التحقق مما إذا تم العثور على الكائن mark
        if ($mark) {
            $student = $mark->student;
    
            // التحقق مما إذا كان الطالب موجودًا
            if ($student) {
                return 'تم تعديل علامة الطالب: ' . $student->user->first_name . ' ' . $student->user->last_name . "\n" . ' في مادة : ' . ($mark->subject ? $mark->subject->name : 'مادة غير معروفة');
            } else {
                return 'تم تعديل علامة لطالب غير معروف';
            }
        } else {
            return 'لم يتم العثور على العلامة بالرقم المحدد';
        }
    }
    

    //تم تجربته
    //رفع وظيفة
    if (preg_match('/^api\/teacher\/upload_homework$/', $path, $matches)) {
    
        
        $user= auth()->user()->id;
        $teacher = \App\Models\Teacher::where('user_id', auth()->user()->id)->first();
        $subject = DB::table('teacher_subjects')->where('teacher_id','=',$teacher->id)->first();
    
        $subject = \App\Models\Subject::find($subject->id);

        $message = 'تم رفع وظيفة لمادة '.$subject->name ;
        $message .= 'بالوصف التالي: '.$request->description;
        $message .= 'من قبل الأستاذ: '. $teacher->user->first_name.' '.$teacher->user->last_name;
        
        return $message;
    }


    if (preg_match('/^api\/teacher\/delete_homework\/(\d+)$/', $path, $matches)) {

        $homeworkId = $matches[1];

        $homework = \App\Models\Homework::find($homeworkId);
        $user= auth()->user()->id;
        $teacher = \App\Models\Teacher::where('user_id', auth()->user()->id)->first();
        // $subject = DB::table('teacher_subjects')->where('teacher_id','=',$teacher->id)->first();
    
        // $subject = \App\Models\Subject::find($subject->id);


        $message = 'تم حذف وظيفة لمادة '.$homework->subject->name ;
        $message .= 'ذات الوصف التالي: '.$request->description;
        $message .= 'من قبل الأستاذ: '. $teacher->user->first_name.' '.$teacher->user->last_name;
        
        return $message;

    }

    if (preg_match('/^api\/teacher\/edit_comment\/(\d+)$/', $path, $matches)) {
        $commentId = $matches[1]; // استخراج معرف التعليق
    
        // جلب بيانات التعليق
        $comment = \App\Models\Comment::find($commentId);
    
        if (!$comment) {
            return 'تم تعديل تعليق غير معروف (ID: ' . $commentId . ')';
        }
    
        $teacher = \App\Models\Teacher::where('user_id', auth()->user()->id)->first();
  
        // $commentData = $request->all();
    
    
        // تكوين رسالة لتسجيل النشاط
        $message = 'تم تعديل الأستاذ: ' . $teacher->user->first_name . ' ' . $teacher->user->last_name .' تعليقه'."\n".
                   ' على المنشور: "' . ($comment->post->quostion ?? 'بدون عنوان') . "\n".
                   'حيث كان: '. $comment->description."\n".
                   'التعليق الحديث: '. $request->description;
                //    ' بتاريخ: ' . now()->toDateTimeString();
    
        return $message;
    }
    
     
      
    
    


    

    
    

    // بقية الحالات
    switch ($path) {
        case 'api/monetor/add_publish':
            return 'تم إضافة إعلان جديد';
        case 'api/monetor/delete_publish/{publish_id}':
            return 'تم حذف إعلان';
        default:
            return 'قام المستخدم بطلب ' . $path;
    }
}



}
