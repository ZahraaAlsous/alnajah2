<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FcmService;
use App\Notifications\MyNotification;
use App\Models\User;
use App\Models\Student;
use App\Models\Parentt;
use App\Models\Order;
use App\Models\Classs;
use App\Models\Section;

class NotificationController extends Controller
{
    private $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function sendNotification(Request $request)
{
    $fcm_token = $request->input('fcm_token');
    $title = $request->input('title');
    $body = $request->input('body');

    // إرسال الإشعار عبر FCM
    $response = $this->fcmService->sendNotification($fcm_token, $title, $body);

    $user = User::where('fcm_token', $fcm_token)->first();
    if ($user) {
        $user->notify(new MyNotification($title, $body));
    }

    return response()->json($response);
}




public function display_notification()
{
    $user = auth()->user();
    if ($user instanceof Parentt || $user instanceof User) {
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->take(20)->get();

        $formattedNotifications = $notifications->map(function ($notification) {
            $data = $notification->data;

            $data['title'] = json_decode('"' . $data['title'] . '"');
            $data['body'] = json_decode('"' . $data['body'] . '"');
            $data['created_at'] = $notification->created_at;

            return $data;
        });

        return response()->json($formattedNotifications);
    } else {
        return response()->json(['error' => 'Invalid user type'], 403);
    }
}


    public function sendNotification_call($fcm_token, $title, $body)
{
 // إرسال الإشعار عبر FCM
 $response = $this->fcmService->sendNotification($fcm_token, $title, $body);

 // تخزين الإشعار في قاعدة البيانات
 $user = User::where('fcm_token', $fcm_token)->first(); // أو يمكنك استخدام user_id من الطلب
 if ($user) {
     $user->notify(new MyNotification($title, $body)); // تخزين الإشعار في قاعدة البيانات
 }

 return response()->json($response);
}


//-----------------
//كل طلاب شعبة
public function sendNotification_student_section($title,$body,$section_id)
{
    $students = Student::where('section_id',$section_id)->get();
        // $fcm =
        foreach ($students as $student) {


            $fcm = $student->user->fcm_token;
            $fcm_token = $fcm;

            // $notificationController = new NotificationController();

            $this->sendNotification_call($fcm_token, $title,$body);
        }
}

public function sendNotification_student_class($title,$body,$class_id)
{
    $sections = Section::where('class_id',$class_id)->get();

    foreach ($sections as $section) {
        $this->sendNotification_student_section($title,$body,$section_id);
    }
}

//كل المديرين
public function sendNotification_for_all_admin($title,$body)
{
    $users = User::where('user_type','admin')->where('status','1')->get();
        // $fcm =
        foreach ($users as $user) {


            $fcm = $user->fcm_token;
            $fcm_token = $fcm;

            // $notificationController = new NotificationController();

            $this->sendNotification_call($fcm_token, $title,$body);
        }
}

//كل الموجهين
public function sendNotification_for_all_monetor($title,$body)
{
    $users = User::where('user_type','monetor')->where('status','1')->get();
        // $fcm =
        foreach ($users as $user) {


            $fcm = $user->fcm_token;
            $fcm_token = $fcm;

            // $notificationController = new NotificationController();

            $this->sendNotification_call($fcm_token, $title,$body);
        }
}


//لأهل طالب محدد
//يجب تجريب
public function sendNotification_for_parent($title,$body,$student_id)
{
    $student = Student::where('id',$student_id)->first();
    // $parennt_id = $student->parentt->id;
    if ($student && $student->parentt) {
        $parennt_id = $student->parentt->id;
        // متابعة الكود...
    } else {
        return response()->json(['error' => 'Student or parent not found'], 404);
    }
    
 
    $parennt = Parentt::where('id', $parennt_id)->first();
    $fcm_token = $parennt->fcm_token;
 // إرسال الإشعار عبر FCM
 $response = $this->fcmService->sendNotification($fcm_token, $title, $body);

 // تخزين الإشعار في قاعدة البيانات
 $parennt = Parentt::where('fcm_token', $fcm_token)->first(); // أو يمكنك استخدام user_id من الطلب
 if ($parennt) {
     $parennt->notify(new MyNotification($title, $body)); // تخزين الإشعار في قاعدة البيانات
 }

 return response()->json($response);
}

//لأهل و طلاب شعبة محددة
public function sendNotification_for_parent_and_student($title,$body,$section_id)
{
        $students = Student::where('section_id',$section_id)->get();
        // $fcm =
        foreach ($students as $student) {

            $fcm = $student->user->fcm_token;
            $fcm_token = $fcm;

            $this->sendNotification_call($fcm_token, $title,$body);

            $parennt = Parentt::where('id', $parennt_id)->first();
            $fcm_token_p = $parennt->fcm_token;
            $response = $this->fcmService->sendNotification($fcm_token_p, $title, $body);

            // تخزين الإشعار في قاعدة البيانات
            $parennt = Parentt::where('fcm_token', $fcm_token_P)->first(); // أو يمكنك استخدام user_id من الطلب
            if ($parennt) {
                $parennt->notify(new MyNotification($title, $body)); // تخزين الإشعار في قاعدة البيانات
            }

        }

}

//كل المستخدمين الفعالة حساباتهم
public function sendNotification_all_user_live($title,$body)
{
    $users = User::where('status','1')->get();
        // $fcm =
        foreach ($users as $user) {


            $fcm = $user->fcm_token;
            $fcm_token = $fcm;

            // $notificationController = new NotificationController();

            $this->sendNotification_call($fcm_token, $title,$body);
        }
}

public function sendNotification_all_student_course($title,$body,$course_id)
{
    $orders = Order::where('course_id', $course_id)
                   ->where('student_type', '11')->whereNotNull('student_id')
                   ->get();
    
                   foreach ($orders as $order) {


                    $fcm = $order->student->user->fcm_token;
                    $fcm_token = $fcm;
        
                    // $notificationController = new NotificationController();
        
                    $this->sendNotification_call($fcm_token, $title,$body);
                }


}



//يجب تجريبه
// public function sendNotification_fee($student_id)
// {
//     // $student = Student::where('id',$student_id)->get();

//     // $parennt_id = $student->parentt->id;

//     // $parennt = Parentt::where('id', $parennt_id)->first();
//     $student = Student::find($student_id);
//     // $parennt_id = $student->parentt->id;
//     if ($student && $student->parentt) {
//         $parennt_id = $student->parentt->id;
//         // متابعة الكود...
//     } else {
//         return response()->json(['error' => 'Student or parent not found'], 404);
//     }
    
 
//     // $parennt = Parentt::where('id', $parennt_id)->first();
//     // $fcm_token_p = $parennt->fcm_token;

//     $fcm = $student->user->fcm_token;
//     $fcm_token_s = $fcm;

//     $title = 'إنذار';
//     $body = 'يرجى دفع القسط';

//     $p = $this->sendNotification_for_parent($title,$body,$student_id);
//     $s = $this->sendNotification_call($fcm_token_s, $title,$body);
//     if ($p && $s) {
//         // $parennt->notify(new MyNotification($title, $body)); // تخزين الإشعار في قاعدة البيانات
//         // $student->notify(new MyNotification($title, $body)); // تخزين الإشعار في قاعدة البيانات
//         return 'تم إرسال إنذار';
//     }
//     // if ($student) {
//     //     $student->notify(new MyNotification($title, $body)); // تخزين الإشعار في قاعدة البيانات
//     // }

//     return '--------------------تم إرسال إنذار';

// }


public function sendNotification_fee($student_id)
{
    $student = Student::where('id',$student_id)->first();
    if ($student && $student->parentt) {
        $parennt_id = $student->parentt->id;
    } else {
        return response()->json(['error' => 'Student or parent not found'], 404);
    }
    
    $fcm = $student->user->fcm_token;
    $fcm_token_s = $fcm;

    $title = 'إنذار';
    $body = ' يرجى دفع القسط للطالب'. $student->user->first_name . ' '. $student->user->last_name;
    $body2 = 'يرجى تسديد القسط';

    $p = $this->sendNotification_for_parent($title,$body,$student_id);
    $s = $this->sendNotification_call($fcm_token_s, $title,$body2);
    if ($p && $s) {
        return 'تم إرسال إنذار';
        return response()->json('تم إرسال إنذار',200);
    }
    return response()->json('فشلت عملية إرسال إنذار',300);

}





}
