<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classs;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Parentt;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Teacher_subject;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
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

    public function register_student(Request $request,$order_id)
    {

        $order = Order::find($order_id)->where('id',$order_id)->get;
        $validator = Validator::make($request->all(),[
            'mother_name' => 'required|string',
           // 'email'=>'required|email',
            //'password' => 'required|min:8',
            //'conf_password' => 'required|min:8',
            'user_type' => 'required|default:student',


        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $email = $order->first_name.Str::random(5)."@gmail.com";
        $password = $order->first_name.Str::random(6);
        $user = new User();

        $user->first_name = $order->first_name;
        $user->last_name = $order->last_name;
        $user->father_name = $order->father_name;
        $user->mother_name = $request->mother_name;
        $user->birthday = $order->birthday;
        $user->gender = $order->gender;
        $user->phone = $order->phone;
        $user->address = $order->address;
        $user->year = $order->year;
        $user->image = $order->image;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->conf_password = Hash::make($password);
        $user->user_type = $request->user_type;


        $user->save();

        $validator1 = Validator::make($request->all(),[
            'calssification' => 'required|string',
            'school_tuition' => 'required',
            'class_id'=> 'required',
            'section_id'=>'required',
            'parentt_id'=>'required',
        ]);

        if ($validator1->fails()) {
            return response()->json(['errors' => $validator1->errors()]);
        }

        $student = new Student;
        $student->calssification = $request->calssification;
        $student->school_tuition = $request->school_tuition;
        $student->user_id = $user->id;
        $student->class_id =$request->class_id;
        $student->section_id= $request->section_id;
        $student->parentt_id = $request->parentt_id;

        $student->save();


    }

    public function register_parentt(Request $request){

        $validator = Validator::make($request->all(),[
            'first_name'=>'required|string',
            'last_name' => 'required|string',
            'phone' => 'required',
            'address' => 'required',
            'email'=>'required|email',
            'password' => 'required|min:8',
            'conf_password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $parentt =new Parentt();
        $parentt->first_name = $request->first_name;
        $parentt->last_name = $request->last_name;
        $parentt->phone = $request->phone;
        $parentt->address = $request->address;
        $parentt->email = $request->email;
        $parentt->password = Hash::make($request->password);
        $parentt->conf_password = Hash::make($request->conf_password);

        $parentt->save();



    }

    public function get_profile_user(){

        return response()->json([auth()->user(),'this is user profile']);

    }

public function get_teacher_profile($teacher_id)
{
        $teacher = Teacher::find($teacher_id);
        if(!$teacher)
        {
            return response()->json(['the teacher not found']);
        }

        $teacher1 = $teacher->user;
        $section = $teacher->section;
        $subject= $teacher->subject;

        return response()->json([$teacher,$section]);
    }


    public function update_teacher_profile(Request $request,$teacher_id)
    {
        $teacher = Teacher::find($teacher_id);
        if(!$teacher){
            return response()->json(['teacher not found ']);
        }

        $validator = validator::make($request->all(),[
            'cost_hour'=>'required',
            'certificate'=>'required',
            'classs_id'=>'required',
            'name_subject' => 'required|string|exists:subjects,name',
        ]);
        if($validator->fails())
        {
            return response()->json(['Please validate error',$validator->errors()]);
        }
        $subject = Subject::where('name', $request->name_subject)->first();
        if (!$subject) {
        return response()->json([
            'status' => 'false',
            'message' => 'Subject not found for the specified class_id',
        ]);
       }

     $teacher->update([
        'cost_hour'=>$request->cost_hour,
        'num_our_added'=>$request->num_hour_added,
        'note_hour_added'=>$request->note_hour_added,
        'certificate'=>$request->certificate,
        'classs_id'=>$request->classs_id,
     ]);

     $teacher_subject = Teacher_subject::where('teacher_id',$teacher_id)->first();
     $teacher_subject->update([
     'subject_id' => $subject->id,
     'teacher_id' => $teacher->id,
     ]);

    return response()->json([
        'status' => 'true',
        'message' => 'Teacher profile updated successfully',
    ], 200); // إضافة كود الحالة 200 للاستجابة الناجحة


    }

//     public function update_teacher_profile(Request $request, $teacher_id)
// {
//     // العثور على المعلم
//     $teacher = Teacher::find($teacher_id);
//     if (!$teacher) {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'Teacher not found'
//         ], 404); // إضافة كود الحالة 404 للعنصر غير الموجود
//     }

//     // التحقق من صحة الإدخال
//     $validator = Validator::make($request->all(), [
//         'cost_hour' => 'required',
//         'certificate' => 'required',
//         'class_id' => 'required',
//         'name_subject' => 'required|string|exists:subjects,name',
//     ]);

//     // التحقق من الأخطاء
//     if ($validator->fails()) {
//         return response()->json([
//             'status' => 'false',
//             'errors' => $validator->errors(),
//         ], 400); // إضافة كود الحالة 400 للخطأ في الإدخال
//     }

//     // العثور على المادة المطلوبة
//     $subject = Subject::where('name', $request->name_subject)->first();
//     if (!$subject) {
//         return response()->json([
//             'status' => 'false',
//             'message' => 'Subject not found for the specified class_id',
//         ], 404);
//     }

//     // يمكن هنا إضافة المزيد من المنطق لتحديث معلومات المعلم حسب الحاجة

//     return response()->json([
//         'status' => 'true',
//         'message' => 'Teacher profile updated successfully',
//     ], 200); // إضافة كود الحالة 200 للاستجابة الناجحة
// }


    public function update_profile_user(Request $request ,$id)
{

    $validator = Validator::make($request->all(),[
    'first_name'=>'required',
    'last_name'=>'required',
    'father_name'=>'required',
    'mother_name'=>'required',
    'birthday'=>'required',
    'phone'=>'required',
    'address'=>'required',
    'image'=>'required',

    ]);
    if($validator->fails())
    {
        return response()->json(['Please validate error',$validator->errors()]);
    }
    $user =User::where('id',$id)->first();

    $user->update([
    'first_name'=>$request->first_name,
    'last_name'=>$request->last_name,
    'father_name'=>$request->father_name,
    'mother_name'=>$request->mother_name,
    'birthday'=>$request->birthday,
    'phone'=>$request->phone,
    'address'=>$request->address,
    'image'=>$request->image,

]);


    return response()->json([$user,'the user updated succeflly']);

}

public function update_profile_employee(Request $request , $employee_id)
{
        $employee = Employee::find($employee_id);
        if(!$employee){
            return response()->json(['employee not found ']);
        }
        
        $validator = validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'salary' => 'required',
            'type' => 'required',
        ]);
        if($validator->fails())
        {
            return response()->json(['Please validate error',$validator->errors()]);
        }

    $employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'phone' => $request->phone,
            'address' => $request->address,
            'salary' => $request->salary,
            'type' => $request->type,
        ]);



    return response()->json([$employee, 'the user updated successfully']);

}

// public function display_notification()
// {
//     $user = User::find(auth()->user()->id); // استبدل بمعرف المستخدم المناسب
//     $notifications = $user->unreadNotifications;
//     return response()->json($notifications);
// }

// public function display_notification()
// {
//     $user = auth()->user(); // يجلب المستخدم الذي قام بتسجيل الدخول

//     // // تحقق ما إذا كان المستخدم هو Parent أو User
//     // if (Parentt::where("email", auth()->user()->email)) {
//     //     $notifications = $user->unreadNotifications;
//     // } elseif (User::where("email", auth()->user()->email)) {
//     //     $notifications = $user->unreadNotifications;
//     // } else {
//     //     return response()->json(['error' => 'Invalid user type'], 403);
//     // }

//     // return response()->json($notifications);

//     // عند استرجاع الإشعارات من قاعدة البيانات
//  $notifications = $user->notifications;

//  foreach ($notifications as $notification) {
//     echo $notification->data['body']; // يجب أن يعرض النص بشكل صحيح
//  }

// }

// public function display_notification()
// {
//     $user = auth()->user(); // يجلب المستخدم الذي قام بتسجيل الدخول

//     // تحقق من نوع المستخدم وإحضار الإشعارات غير المقروءة
//     if ($user instanceof Parentt || $user instanceof User) {
//         // جلب جميع الإشعارات
//         $notifications = $user->notifications;

//         // فك ترميز النصوص
//         $formattedNotifications = $notifications->map(function ($notification) {
//             $data = $notification->data;

//             $data['title'] = json_decode('"' . $data['title'] . '"');
//             $data['body'] = json_decode('"' . $data['body'] . '"');
//             $data['created_at'] = $notification->created_at;

//             return $data;
//         });

//         // إرجاع الإشعارات بصيغة JSON
//         return response()->json($formattedNotifications);
//     } else {
//         return response()->json(['error' => 'Invalid user type'], 403);
//     }
// }

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






}
