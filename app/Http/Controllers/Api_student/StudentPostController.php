<?php

namespace App\Http\Controllers\Api_student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class StudentPostController extends Controller
{
        //عرض جميع المناقشات الخاصة بشعبة الطالب فقط عنوان و اسم المدرس
        public function displayAllPost()
        {
            $student = Student::where('user_id', auth()->user()->id)->first();
            $post = Post::where('section_id',$student->section_id)->with('subject')->with('teacher.user')->get();
            return $post;
        }
    
        //عرض مناقشة محددة التعليقات و السؤال
        public function displayPost($post_id)
        {
            $post = Post::where('id',$post_id)->with('comments.student.user')->with('comments.teacher.user')->orderBy('created_at')->first();
            return $post;
        }
    
        // //إضافة تعليق لمناقشة محددة من قبل طالب أو أستاذ
        // public function addComment(Request $request, $post_id)
        // {
        //     $comment = new Comment;
    
        //     $comment->description = $request->description;
        //     $comment->post_id = $post_id;
    
        //     if(auth()->user()->user_type == 'student')
        //     {
        //         $student = Student::where('user_id', auth()->user()->id)->first();
        //         $comment->student_id = $student->id;
        //     }
        //     elseif(auth()->user()->user_type == 'teacher')
        //     {
        //         $teacher = Teacher::where('user_id', auth()->user()->id)->first();
        //         $comment->teacher_id = $teacher->id;
        //     }
        //     $comment->save();
        // }

         //إضافة تعليق لمناقشة محددة من قبل طالب أو أستاذ
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
     //تعديل تعليق
     public function editComment(Request $request, $comment_id)
     {
         $comment = Comment::find($comment_id);
         if (!$comment) {
            return 'no have comment';
         }
         $post = Post::where('id', $comment->post_id)->first();

         if ($post->state_on_off == 1) {
            if (!$comment) {
                return ['err' => 'not found'];
            }
   
            elseif ($comment->student_id != null) {
                $student = Student::where('user_id', auth()->user()->id)->first();
                $comment2=Comment::where('id',$comment_id)->where('student_id', $student->id)->first();
                
                $comment2->description = $request->description;
                $comment2->save();
                return $comment2;
            }
   
            elseif ($comment->teacher_id != null) {
                $teacher = Teacher::where('user_id', auth()->user()->id)->first();
                $comment2=Comment::where('id',$comment_id)->where('teacher_id', $teacher->id)->first();
                
                $comment2->description = $request->description;
                $comment2->save();
                return $comment2;
                
            }
   
            return 'you can not edit this comment'; 
         }

         elseif ($post->state_on_off == 0 && $comment->teacher_id != null) {
            $teacher = Teacher::where('user_id', auth()->user()->id)->first();
            $comment2=Comment::where('id',$comment_id)->where('teacher_id', $teacher->id)->first();
            
            $comment2->description = $request->description;
            $comment2->save();
            return $comment2;
        }
     }
}
