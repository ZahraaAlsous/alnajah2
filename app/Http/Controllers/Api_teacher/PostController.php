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


class PostController extends Controller
{
    //إنشاء مناقشة لشعبة محددة
    // public function create_post(Request $request, $section_id)
    // {
    //     $teacher = Teacher::where('user_id', auth()->user()->id)->first();
    //     $post = new Post;

    //     $post->quostion = $request->quostion;
    //     $post->year = Auth()->user()->year;
    //     $post->subject_id = $teacher->subject_id;
    //     $post->section_id = $section_id;
    //     $post->teacher_id = $teacher->id;

    //     $post->save();

    //     return $post;
    // }
    public function create_post(Request $request, $section_id, NotificationController $notificationController)
    {
        $teacher = Teacher::where('user_id', auth()->user()->id)->first();
        $subject = DB::table('teacher_subjects')->where('teacher_id','=',$teacher->id)->first();
        $post = new Post;

        $post->quostion = $request->quostion;
        $post->year = Auth()->user()->year;
        // $post->subject_id = $teacher->subject_id;
        $post->subject_id = $subject->subject_id;
        $post->section_id = $section_id;
        $post->teacher_id = $teacher->id;

        // $post->save();

        if ($post->save()) {
        $title = 'مناقشة جديدة';
        $body = $post->quostion;
        $notificationController->sendNotification_student_section($title,$body,$section_id);
        $notificationController->sendNotification_for_all_admin($title,$body);
        $notificationController->sendNotification_for_all_monetor($title,$body);

        }

        
        return $post;
    }

    //عرض مناقشاته
    public function display_post()
    {
        $teacher = Teacher::where('user_id', auth()->user()->id)->first();

        $post = Post::where('teacher_id', $teacher->id)->get();

        return $post;
    }

    //عرض مناقشة محددة التعليقات و السؤال
    public function displayPost($post_id)
    {
        $post = Post::where('id',$post_id)->with('comments.student.user')->with('comments.teacher.user')->first();
        return $post;
    }

    //إضافة تعليق لمناقشة محددة من قبل طالب أو أستاذ
    // public function addComment(Request $request, $post_id, NotificationController $notificationController)
    // {
    //     $comment = new Comment;

    //     $comment->description = $request->description;
    //     $comment->post_id = $post_id;

    //     $post = Post::where('id', $post_id)->first();
    //     if ($post->state_on_off == 1) {
    //         if(auth()->user()->user_type == 'student')
    //         {
    //             $student = Student::where('user_id', auth()->user()->id)->first();
    //             $comment->student_id = $student->id;
    //         }
    //         elseif(auth()->user()->user_type == 'teacher')
    //         {
    //             $teacher = Teacher::where('user_id', auth()->user()->id)->first();
    //             $comment->teacher_id = $teacher->id;
    //         }
    //         $comment->save();

    //         $title = 'تعليق جديد';
    //         $body = $comment->description;
    //         $section_id = $post->section_id;
    //         $notificationController->sendNotification_student_section($title,$body,$section_id);
    //         $teacher = Teacher::where('id',$post->teacher_id);

    //         sendNotification_call($fcm_token, $title,$body);



    //         return $comment;
    //     }

    //     elseif($post->state_on_off == 0) {
    //         if(auth()->user()->user_type == 'teacher')
    //         {
    //             $teacher = Teacher::where('user_id', auth()->user()->id)->first();
    //             $comment->teacher_id = $teacher->id;
    //             $comment->save();

    //             return $comment;
    //         }  
    //     }
    // }
    public function addComment(Request $request, $post_id, NotificationController $notificationController)
    {
        $comment = new Comment;

        $comment->description = $request->description;
        $comment->post_id = $post_id;

        $post = Post::where('id', $post_id)->first();
        if ($post->state_on_off == 1) {
            if(auth()->user()->user_type == 'student')
            {
                $student = Student::where('user_id', auth()->user()->id)->first();
                $comment->student_id = $student->id;
                $body = 'علق '.auth()->user()->first_name .' '. auth()->user()->last_name.' في المناقشة '.$post->quostion;
            }
            elseif(auth()->user()->user_type == 'teacher')
            {
                $teacher = Teacher::where('user_id', auth()->user()->id)->first();
                $comment->teacher_id = $teacher->id;
                $body = 'علق الأستاذ '.auth()->user()->first_name .' '. auth()->user()->last_name.' في المناقشة '.$post->quostion;
            }
            // $comment->save();

            if ($comment->save()) {
                $title = 'تعليق جديد';

                $notificationController->sendNotification_student_section($title,$body,$post->section_id);
                $notificationController->sendNotification_call($post->teacher->user->fcm_token, $title, $body);

            }

             return response()->json([
                'user' => auth()->user(),
                'comment' => $comment
            ]);
        }

        elseif($post->state_on_off == 0) {
            if(auth()->user()->user_type == 'teacher')
            {
                $teacher = Teacher::where('user_id', auth()->user()->id)->first();
                $comment->teacher_id = $teacher->id;
                $comment->save();
            }
            else {
                return response()->json('the post off , you can not comment');
            }
            
        }

        // if(auth()->user()->user_type == 'student')
        // {
        //     $student = Student::where('user_id', auth()->user()->id)->first();
        //     $comment->student_id = $student->id;
        // }
        // elseif(auth()->user()->user_type == 'teacher')
        // {
        //     $teacher = Teacher::where('user_id', auth()->user()->id)->first();
        //     $comment->teacher_id = $teacher->id;
        // }
        // $comment->save();
    }

     //تعديل تعليق
     public function editComment(Request $request, $comment_id)
     {
         $comment = Comment::find($comment_id);
         $post = Post::where('id', $comment->post_id)->first();

         if ($post->state_on_off == 1) {
            if (!$comment) {
                return ['err' => 'not found'];
            }
   
            elseif ($comment->student_id != null) {
                $student = Student::where('user_id', auth()->user()->id)->first();
                $comment2=Comment::where('student_id', $student->id)->where('id',$comment_id)->first();
                
                $comment2->description = $request->description;
                $comment2->save();
                return $comment2;
                return response()->json(['message' => 'you can not edit this comment'], 200); 
            }
   
            elseif ($comment->teacher_id != null) {
                $teacher = Teacher::where('user_id', auth()->user()->id)->first();
                $comment2=Comment::where('teacher_id', $teacher->id)->where('id',$comment_id)->first();
                
                $comment2->description = $request->description;
                $comment2->save();
                return $comment2;
                
            }
   
            return 'you can not edit this comment'; 
         }

         elseif ($post->state_on_off == 0 && $comment->teacher_id != null) {
            $teacher = Teacher::where('user_id', auth()->user()->id)->first();
            $comment2=Comment::where('teacher_id', $teacher->id)->first();
            
            $comment2->description = $request->description;
            $comment2->save();
            return $comment2;
        }
     }

             //حذف تعليق من قبل طالب أو أستاذ مع العلم يستطيع أي مستخدم حذف تعليق طالب عدا طالب آخر
             public function deleteComment ($comment_id)
             {
                 $student = Student::where('user_id', auth()->user()->id)->first();
                 $teacher =$teacher = Teacher::where('user_id', auth()->user()->id)->first();
                 $comment = Comment::where('id', $comment_id)->first();
     
                 if ($comment->student_id != NULL && auth()->user()->user_type == 'student') {
                     $comment = Comment::where('id', $comment_id)->where('student_id', $student->id)->delete();
                     return auth()->user();
                 }
     
                 elseif ($comment->teacher_id != NULL && auth()->user()->user_type == 'teacher') {
                     $comment_t = Comment::where('id', $comment_id)->where('teacher_id', $teacher->id)->delete();
                     return auth()->user();
                 }
     
                 elseif (auth()->user()->user_type != 'student')
                 {
                     $comment_t = Comment::where('id', $comment_id)->delete();
                     return auth()->user();
                 }
     
                 return 'you can not delete this comment';
             }
        
    //إنهاء مناقشة
    // public function off_on_post($post_id)
    // {
    //     $post = Post::where('id', $post_id)->first();
    //     $teacher = Teacher::where('user_id', auth()->user()->id)->first();
    //     if ( auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'monetor' || $teacher->id == $post->teacher_id) {
    //         if ($post->state_on_off == 1) {
    //             $post->update(['state_on_off' => 0]);
                
    //             return auth()->user();
    //         }
    //         $post->update(['state_on_off' => 1]);
    //         return auth()->user();
    //     }
    //     return 'you can not do';
    // } 

    public function off_on_post($post_id, NotificationController $notificationController)
    {
        $post = Post::where('id', $post_id)->first();
        $teacher = Teacher::where('user_id', auth()->user()->id)->first();
        if ( auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'monetor' || $teacher->id == $post->teacher_id) {
            if ($post->state_on_off == 1) {
                $post->update(['state_on_off' => 0]);
                $body = 'تم إيقاف المناقشة من قبل ' . auth()->user()->first_name .' '. auth()->user()->last_name;
                // if (auth()->user()->user_type != 'teacher') {
                //     $post->teacher->user->notify(new MyNotification($message));
                // }
                $title = 'حالة مناقشة';
            // $post->teacher->user->notify(new MyNotification($message));
            $notificationController->sendNotification_student_section($title,$body,$post->section_id);
            $notificationController->sendNotification_for_all_admin($title,$body);
            $notificationController->sendNotification_for_all_monetor($title,$body);
            $teacher = Teacher::find($post->teacher_id); 
            $notificationController->sendNotification_call($teacher->user->fcm_token, $title, $body);
                return auth()->user();
            }
            elseif ($post->state_on_off == 0) {
                $post->update(['state_on_off' => 1]);
                $body = 'تم تفعيل مناقشة من قبل ' . auth()->user()->first_name .' '. auth()->user()->last_name;
            }

            $title = 'حالة مناقشة';
            // $post->teacher->user->notify(new MyNotification($message));
            $notificationController->sendNotification_student_section($title,$body,$post->section_id);
            $notificationController->sendNotification_for_all_admin($title,$body);
            $notificationController->sendNotification_for_all_monetor($title,$body);
            $teacher = Teacher::find($post->teacher_id); 
            $notificationController->sendNotification_call($teacher->user->fcm_token, $title, $body);
            

            return auth()->user();
        }
        return 'you can not do';
    }
}

