<?php

namespace App\Http\Controllers\Api_admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Breake;
use App\Models\Classs;
use App\Models\Student;
use App\Models\Parentt;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Employee;
use App\Models\Expenses;
use App\Models\Mark;
use Illuminate\Support\Str;
use App\Models\Note;
use App\Models\Note_Student;
use App\Models\Publish;
use Illuminate\Support\Carbon;
use App\Models\Out_Of_Work_Employee;
use App\Models\Out_Of_Work_Student;
use App\Models\Teacher_Schedule;
use App\Models\Out__Of__Work__Employee;
use App\Models\Image;
use App\Models\Academy;
use App\Models\File_Archive;
use App\Models\File_course;
use App\Models\Image_Archive;
use App\Models\Maturitie;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher_subject;
use App\Models\Hour_Added;
class AdminOperationController extends BaseController
{

//     public function login(Request $request)
//     {
//         $request->validate([
//             "email" => "required|email",
//             "password" => "required"
//         ]);

//         // check email
//         $user = User::where("email", "=", $request->email)->where('status' , 1);
// if($user){
//     $user = User::where("email", "=", $request->email)->first();
//     if($user->status == 0){
//     if(isset($user->id)){
//         if(Hash::check($request->password, $user->password)){
//             // create a token
//             $token = $user->createToken("auth_token")->plainTextToken;
//             /// send a response
//             return response()->json([
//         'User login successfully',
//         'token'=>$token,
//     ]);
//         }
//     }
// }
//     else{
//         $parent = Parentt::where("email", "=", $request->email)->first();
//     if(isset($parent->id)){
//         if(Hash::check($request->password, $parent->password)){
//             // create a token
//             $token = $parent->createToken("auth_token")->plainTextToken;
//             /// send a response
//             return response()->json([
//         'User login successfully',
//         'token'=>$token,
//     ]);
//         }
//     }else{
//         return $this->responseError(['please  check your Auth','auth error']);
//     }

//     }
//     return $this->responseError(['please  check your Auth','auth error']);
// }
//     }
// public function login(Request $request)
//     {
//         $request->validate([
//             "email" => "required|email",
//             "password" => "required"
//         ]);

//         // check email
//         $user = User::where("email", "=", $request->email);
// if($user){
//     $user = User::where("email", "=", $request->email)->first();
//     if(isset($user->id)){
//         if(Hash::check($request->password, $user->password)){
//             // create a token
//             $token = $user->createToken("auth_token")->plainTextToken;
//             /// send a response
//             return response()->json([
//         'User login successfully',
//         'token'=>$token,
//         'user' => $user,
//     ]);
//         }
//     }else{
//         $parent = Parentt::where("email", "=", $request->email)->first();
//     if(isset($parent->id)){
//         if(Hash::check($request->password, $parent->password)){
//             // create a token
//             $token = $parent->createToken("auth_token")->plainTextToken;
//             /// send a response
//             return response()->json([
//         'User login successfully',
//         'token'=>$token,
//     ]);
//         }
//     }else{
//         return $this->responseError(['please  check your Auth','auth error']);
//     }

//     }
//     return $this->responseError(['please  check your Auth','auth error']);
// }
//     }

// public function login(Request $request)
//     {
//         $request->validate([
//             "email" => "required|email",
//             "password" => "required"
//         ]);

//         // check email
//         $user = User::where("email", "=", $request->email);
// if($user){
//     $user = User::where("email", "=", $request->email)->first();
//     if(isset($user->id) && $user->status == '1'){
//         if(Hash::check($request->password, $user->password)){
//             // create a token
//             $token = $user->createToken("auth_token")->plainTextToken;
//             /// send a response
//             return response()->json([
//         'User login successfully',
//         'token'=>$token,
//         'user' => $user,
//     ]);
//         }
//     }else{
//         $parent = Parentt::where("email", "=", $request->email)->first();
//     if(isset($parent->id) && $parent->status == '1'){
//         if(Hash::check($request->password, $parent->password)){
//             // create a token
//             $token = $parent->createToken("auth_token")->plainTextToken;
//             /// send a response
//             return response()->json([
//         'User login successfully',
//         'token'=>$token,
//     ]);
//         }
//     }else{
//         return $this->responseError(['please  check your Auth','auth error']);
//     }

//     }
//     return $this->responseError(['please  check your Auth','auth error']);
// }
//     }


//كنا رابطين عليه
// public function login(Request $request)
//     {
//         $request->validate([
//             "email" => "required|email",
//             "password" => "required",
//             "fcm_token" => "required"
//         ]);

//         // check email
//         $user = User::where("email", "=", $request->email);
// if($user){
//     $user = User::where("email", "=", $request->email)->first();
//     if(isset($user->id)){
//         if ($user->status == '1') {
//             if(Hash::check($request->password, $user->password)){
//                 // create a token
//                 $token = $user->createToken("auth_token")->plainTextToken;
//                 $user->fcm_token = $request->fcm_token;
//                 /// send a response
//                 if (isset($user->image)) {
//                 $user->image_file_url = asset('/upload/' . $user->image);
//                 }
//                 $user->save();
//                 return response()->json([
//             'User login successfully',
//             'token'=>$token,
//             'user' => $user,
//         ]);
//             }
//         }

//         elseif ($user->status == '0') {
//             // return $this->responseError(['your account lock','auth error']);
//             return 'your account lock';
//         }
//     }else{
//         $parent = Parentt::where("email", "=", $request->email)->first();
//     if(isset($parent->id) && $parent->status == '1'){
//         if(Hash::check($request->password, $parent->password)){
//             // create a token
//             $token = $parent->createToken("auth_token")->plainTextToken;
//             $parent->fcm_token = $request->fcm_token;
//             if (isset($parent->image)) {
//             $parent->image_file_url = asset('/upload/' . $parent->image);
//             }
//             $parent->save();
//             /// send a response
//             return response()->json([
//                 'User login successfully',
//                 'token'=>$token,
//                 'user' => $parent,
//             ]);
//         }
//     }
//     elseif (isset($parent->id) && $parent->status == '0') {
//         // return $this->responseError(['your account lock','auth error']);
//         return 'your account lock';
//     }
//     else{
//         return $this->responseError(['please  check your Auth','auth error']);
//     }

//     }
//     return $this->responseError(['please  check your Auth','auth error']);
// }
//     }

public function login(Request $request)
{
    $request->validate([
        "email" => "required|email",
        "password" => "required",
        "fcm_token" => "required"
    ]);

    $user = User::where("email", "=", $request->email)->first();

    if ($user) {
        if ($user->status == '1') {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken("auth_token")->plainTextToken;
                $user->fcm_token = $request->fcm_token;
                // Generate image URL if image exists
                // $user->image_file_url = isset($user->image) ? asset('/upload/' . $user->image) : null;
                // if ($user->image) {
                //     $user->image_file_url = asset('/upload/' . $user->image);
                // } else {
                //     unset($user->image_file_url); // لا تقم بتحديثه إذا كان null
                // }
                if ($user->image) {
                    $image_file_url = asset('/upload/' . $user->image);
                    $user->save();
                return response()->json([
                    'message' => 'User login successfully',
                    'token' => $token,
                    'user' => $user,
                    'im'=>$image_file_url
                ]);
                } else {
                    // unset($image_file_url); // لا تقم بتحديثه إذا كان null
                    $user->save();
                return response()->json([
                    'message' => 'User login successfully',
                    'token' => $token,
                    'user' => $user,
                    // 'im'=>$image_file_url
                ]);
                }
             
                $user->save();
                return response()->json([
                    'User login successfully',
                    'token' => $token,
                    'user' => $user
                ]);
            }
        } elseif ($user->status == '0') {
            return response()->json(['message' => 'Your account is locked'], 403);
        }
    } else {
        $parent = Parentt::where("email", "=", $request->email)->first();
        if ($parent && $parent->status == '1' && Hash::check($request->password, $parent->password)) {
            $token = $parent->createToken("auth_token")->plainTextToken;
            $parent->fcm_token = $request->fcm_token;
            // $parent->image_file_url = isset($parent->image) ? asset('/upload/' . $parent->image) : null;
           
            if ($parent->image) {
                $image_file_url = asset('/upload/' . $parent->image);
                $parent->save();
            return response()->json([
                'message' => 'User login successfully',
                'token' => $token,
                'user' => $parent,
                'im'=>$image_file_url
            ]);
            } else {
                // unset($image_file_url); // لا تقم بتحديثه إذا كان null
                $parent->save();
            return response()->json([
                'message' => 'User login successfully',
                'token' => $token,
                'user' => $parent,
                // 'im'=>$image_file_url
            ]);
            }
            
         
            $parent->save();
            return response()->json([
                'message' => 'User login successfully',
                'token' => $token,
                'user' => $parent,
                'im'=>$image_file_url
            ]);
        } elseif (isset($parent->id) && $parent->status == '0') {
            return response()->json(['message' => 'Your account is locked'], 403);
        } else {
            return response()->json(['message' => 'Please check your credentials'], 401);
        }
    }

    return response()->json(['message' => 'Authentication failed'], 401);
}





    public function logout(Request $request)
    {
        if(Auth::check()){
            $user = User::where("email", auth()->user()->email);
            //$parentt = Parentt::where("email", auth()->parentt()->email);
            if($user){
                $request->user()->currentAccessToken()->delete();
                return response()->json(['status' => true, 'message' => 'User logged out successfully'], 200);
            }
            elseif ($parentt) {
                $request->parentt()->currentAccessToken()->delete();
                return response()->json(['status' => true, 'message' => 'User logged out successfully'], 200);
            }
            else {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }
    }
    else {
                return response()->json(['status' => false, 'message' => 'User not authenticated'], 401);
            }
    }
//     public function logout(Request $request)
// {
//     if(Auth::check()){
//         $user = User::where("email", auth()->user()->email)->first();
//         if($user){
//             $request->user()->currentAccessToken()->delete();
//             return response()->json(['status' => true, 'message' => 'User logged out successfully'], 200);
//         } else {
//             return response()->json(['status' => false, 'message' => 'User not found'], 404);
//         }
//     } else {
//         return response()->json(['status' => false, 'message' => 'User not authenticated'], 401);
//     }
// }




    public function register_student(Request $request, $order_id,$academy_id)
{

    // جلب الطلب حسب المعرف
    $order = Order::find($order_id);
    $academy = Academy::find($academy_id);
    // التحقق من صحة البيانات الأولية
    $user = new User();
        $email = $order->first_name . Str::random(5) . "@gmail.com";
        $password = $order->first_name . Str::random(6);
        $user->first_name = $order->first_name;
        $user->last_name = $order->last_name;
        $user->father_name = $order->father_name;
        $user->mother_name = $order->mother_name;
        $user->birthday = $order->birthday;
        $user->gender = $order->gender;
        $user->phone = $order->phone;
        $user->address = $order->address;
        $user->year = $academy->year;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->conf_password = Hash::make($password);
        $user->user_type = 'student';
        $user->save();

    // التحقق من صحة البيانات الثانوية
    $validator1 = Validator::make($request->all(), [
        'school_tuition' => 'required',
        'class_id' => 'required',
        'section_id' => 'required',
        'parentt_id' => 'required',
    ]);

    if ($validator1->fails()) {
        return $this->responseError(['errors' => $validator1->errors()]);
    }

    // إنشاء سجل الطالب الجديد
    $student = new Student();
    $student->school_tuition = $request->school_tuition;
    $student->user_id = $user->id;
    $student->class_id = $request->class_id;
    $student->section_id = $request->section_id;
    $student->parentt_id = $request->parentt_id;
    $student->student_type = $order->student_type ?: $request->student_type;

    // تعيين التصنيف إذا كان الطالب من فئة البكالوريا
    if ($request->student_type == 0 ) {
        $validator2 = Validator::make($request->all(), [
            'calssification' => 'required|in:0,1', // 0 للعلمي، 1 للأدبي
        ]);

        if ($validator2->fails()) {
            return $this->responseError(['errors' => $validator2->errors()]);
        }

        $student->calssification = $request->calssification;
    } else {
        $student->calssification = null;
    }

    $student->save();

    // إرجاع بيانات الدخول
    return response()->json([$user->email, $password]);
}


public function register_student1(Request $request,$academy_id){


        $academy = Academy::find($academy_id);
        $validator3 = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'birthday' => 'required|date',
            'gender'=>'required',
            'phone' => 'required',
            'address' => 'required',
            'email'=>'required|email',
            'password' => 'required|min:8',
            'conf_password' => 'required|min:8',
        ]);

        if ($validator3->fails()) {
            return $this->responseError(['errors' => $validator3->errors()]);
        }

        $user = new User();

        $password  = $request->password;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->father_name = $request->father_name;
        $user->mother_name = $request->mother_name;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->year = $academy->year;
        $user->email = $request->email;
        $user->password = Hash::make($password);
        $user->conf_password = Hash::make($password);
        $user->user_type = 'student';
        $user->save();

        $validator1 = Validator::make($request->all(), [
            'school_tuition' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'parentt_id' => 'required',
            'student_type'=>'required',
        ]);

        if ($validator1->fails()) {
            return $this->responseError(['errors' => $validator1->errors()]);
        }

        // إنشاء سجل الطالب الجديد
        $student = new Student();
        $student->school_tuition = $request->school_tuition;
        $student->user_id = $user->id;
        $student->class_id = $request->class_id;
        $student->section_id = $request->section_id;
        $student->parentt_id = $request->parentt_id;
        $student->student_type = $request->student_type;

        // تعيين التصنيف إذا كان الطالب من فئة البكالوريا
        if ($request->student_type == 0 ) {
            $validator2 = Validator::make($request->all(), [
                'calssification' => 'required|in:0,1', // 0 للعلمي، 1 للأدبي
            ]);

            if ($validator2->fails()) {
                return $this->responseError(['errors' => $validator2->errors()]);
            }

            $student->calssification = $request->calssification;
        } else {
            $student->calssification = null;
        }

        $student->save();

        // إرجاع بيانات الدخول
        return response()->json([$user->email, $password]);
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
            return $this->responseError(['errors' => $validator->errors()]);
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
        return response()->json([$parentt->email, $parentt->password]);


    }

public function register_teacher(Request $request)
{
    $academy = Academy::find(1);
    $validator = Validator::make($request->all(), [
        'first_name' => 'required',
        'last_name' => 'required',
        'father_name' => 'required',
        'mother_name' => 'required',
        'birthday' => 'required',
        'gender' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'image' => 'nullable',
        // 'fcm_token' => 'required'
    ]);


    if ($validator->fails()) {
        return $this->responseError(['errors' => $validator->errors()]);
    }

    $password = $request->first_name . Str::random(6) ;

    $user = new User();
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->father_name = $request->father_name;
    $user->mother_name = $request->mother_name;
    $user->birthday = $request->birthday;
    $user->gender = $request->gender;
    $user->phone = $request->phone;
    $user->address = $request->address;
    $user->year = $academy->year;
    $user->image = $request->image;
    $user->email = $request->first_name . Str::random(5) . "@gmail.com";
    $user->password = Hash::make($password);
    $user->conf_password = Hash::make($password);
    $user->user_type = 'teacher';
    //إذا هو بدو يعمل الحساب فلازم تتفعل
    // $user->fcm_token = $request->fcm_token;


    $user->save();


    $validator1 = Validator::make($request->all(), [
        'cost_hour' => 'required',
        'certificate'=>'required',
        'class_id'=>'required',
        'name_subject' => 'required|string|exists:subjects,name',
    ]);

    if ($validator1->fails()) {
        return $this->responseError(['errors' => $validator1->errors()]);
    }


    $teacher = new Teacher();
    $teacher->cost_hour = $request->cost_hour;
    $teacher->user_id = $user->id;
    $teacher->certificate = $request->certificate;
    $teacher->classs_id = $request->class_id;





    $teacher->save();

    $subject = Subject::where('name', $request->name_subject)->first();
    if (!$subject) {
        return response()->json([
            'status' => 'false',
            'message' => 'Subject not found for the specified class_id',
        ]);
    }
    $teacher_subject = new Teacher_subject();
    $teacher_subject->subject_id = $subject->id;
    $teacher_subject->teacher_id = $teacher->id;

    $teacher_subject->save();
    return response()->json([$user->email, $password]);

    }

public function register_employee(Request $request)
{
    $academy = Academy::find(1);
    $validator = Validator::make($request->all(), [
        'first_name' => 'required',
        'last_name' => 'required',
        'phone' => 'required',
        'address' => 'required',
        'salary' => 'required',
        'type'=>'required',
    ]);

    if ($validator->fails()) {
        return $this->responseError(['errors' => $validator->errors()]);
    }

    $email = $request->first_name . Str::random(5) . "@gmail.com";
    $password = $request->first_name . Str::random(6);

    $employee = new Employee();
    $employee->first_name = $request->first_name;
    $employee->last_name = $request->last_name;
    $employee->phone = $request->phone;
    $employee->address = $request->address;
    $employee->salary = $request->salary;
    $employee->year = $academy->year;
    $employee->email = $email;
    $employee->password = Hash::make($password);
    $employee->type = $request->type;


    $employee->save();
    return response()->json([$employee->email, $password]);

}



public function delete_student($student_id)
{
    $student = Student::find($student_id);
    if(!$student)
    return response()->json(['the student not found']);
    $student = $student->user;

    if($student->status == 0)
    {
        return response()->json(['the account was stopped before now']);
    }
    $student->update([
        'status' => 0,
    ]);

    return response()->json(['the account is stopped']);
    }

public function delete_parentt($parent_id)
{
    $parent = Parentt::find($parent_id);
    if(!$parent)
    {
        return response()->json(['the parent not found']);
    }
    if($parent->status == 0){
    return response()->json(['the account was stopped']);
    }
    $parent->update([
        'status' => 0,
    ]);
    return response()->json(['the account is stopped']);
}

public function delete_teacher($teacher_id)
{
    $teacher = Teacher::find($teacher_id);
    if(!$teacher)
    return response()->json(['the teacher not found']);
    $teacher = $teacher->user;

    if($teacher->status == 0)
    {
        return response()->json(['the account was stopped before now']);
    }
    $teacher->update([
        'status' => 0,
    ]);

    return response()->json(['the account is stopped']);
    }

    public function get_profile_user(){

        return $this->sendResponse2(auth()->user(),'this is user profile');

    }


    public function registerPost(Request $request, $order_id,$academy_id)
    {
        $order = Order::where('id', $order_id)->first();
        $academy = Academy::find($academy_id);
        $user = new User();

        $user->first_name = $order->first_name;
        $user->last_name = $order->last_name;
        $user->father_name = $order->father_name;
        $user->mother_name = $request->mother_name;
        $user->birthday = $order->birthday;
        $user->gender = $order->gender;
        $user->phone = $order->phone;
        $user->address = $order->address;
        $user->year = $academy->year;
        $user->image = $request->image;
        $user->email = $order->email;
        $user->password = Hash::make($request->password);
        $user->conf_password = Hash::make($request->conf_password);
        $user->user_type = $request->user_type;

        $user->save();

        // create a token
        $token = $user->createToken("auth_token")->plainTextToken;
        /// send a response
        return $token;

    }

    //عرض طلبات التسجيل بالمعهد
    public function DisplayOrderNewStudent()
    {
        $order = DB::table('orders')->where('student_id','=',null)->where('course_id','=',null)->get();

        return $order;
    }

    //إعطاء موعد لطلب تسجيل في المعهد
    public function GiveDate(Request $request, $order_id)
    {
        $order = Order::where('id',$order_id)->first();

        $request->validate([
            "date" => "required|date_format:Y-m-d|after:today"
        ]);

        $new = new Appointment;

        $new->date =$request->date;
        $new->order_id = $order_id;

        $new->save();
    }

public function student_classification($calssification,$year)
{
    if($calssification == 1){
        $stud =Student::where('calssification' ,'=', 1)->where('year',$year)
        ->get();
        foreach($stud as $stud)
        {
            echo $stud->user->first_name ."  " .$stud->user->last_name;
            echo $stud->classs->name;
            echo $stud->section->num_section;
        }

    }
    else {
        $stud =Student::where('calssification' ,'=', 0)->where('year',$year)->get();
        foreach($stud as $stud)
        {
            echo $stud->user->first_name ."  " .$stud->user->last_name;
            echo $stud->classs->name;
            echo $stud->section->num_section;
        }
    }
}

public function disply_all_student_here($year)
{
    $user = User::where('year', '=' ,$year)->get()->all();
    if($user->user_type == 'student')
    {
        return response()->json([$user]);

    }
}

// public function addTeacherSchedule(Request $request,$teacher_id)
// {
//     $teacher = Teacher::find($teacher_id);
//     if(!$teacher)
//     {
//         return response()->json(['the teacher not found']);
//     }

//     // التحقق من صحة المدخلات
//     $validator = Validator::make($request->all(), [
//         // 'teacher_id' => 'required|exists:teachers,id',
//         'schedules' => 'required|array',
//         'schedules.*.day_of_week' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday',
//         'schedules.*.start_time' => 'required|date_format:H:i',
//         'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 422);
//     }

//     // إذا كان التحقق ناجحًا، تابع عملية إضافة الجدول
//     foreach ($request->schedules as $schedule) {
//         Teacher_Schedule::create([
//             'teacher_id' => $teacher_id,
//             'day_of_week' => $schedule['day_of_week'],
//             'start_time' => $schedule['start_time'],
//             'end_time' => $schedule['end_time'],
//         ]);
//     }

//     return response()->json(['message' => 'Schedule added successfully'], 200);
// }


public function updateWeeklySchedule(Request $request, $teacher_id, NotificationController $notificationController)
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
        $title = 'برنامج دوام';
        $body ='برنامج دوام جديد يرجى الالتزام به';
        
        $notificationController->sendNotification_call($teacher->user->fcm_token, $title, $body);
        

        // إرجاع رسالة ناجحة
        return response()->json(['message' => 'Teacher weekly schedule updated successfully'], 200);
}


public function addAbsenceForTeacherandemployee(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'num_hour_out' => 'required|integer',
            // 'note'=>'nullable',
            'teacher_id' => 'nullable|exists:teachers,id',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // التأكد من أن أحدهما فقط تم تقديمه
        if (!$request->hasAny(['teacher_id', 'employee_id'])) {
            return response()->json(['message' => 'Either teacher_id or employee_id must be provided'], 422);
        }

        // إنشاء سجل الغياب
        $absence = new Out_Of_Work_Employee();
        $absence->date = $request->date;
        $absence->num_hour_out = $request->num_hour_out;
        // $absence->note = $request->note;
        $absence->teacher_id = $request->teacher_id;
        $absence->employee_id = $request->employee_id;
        $absence->save();

        return response()->json(['message' => 'Absence added successfully'], 200);
    }





    public function desplay_teacher_salary($teacher_id , $year , $month)
    {
    $teacher = Teacher::with('user')->find($teacher_id);
    if(!$teacher)
    {
        return response()->json(['teacher not found ']);
    }
    $num_work_hour = $this->getteacherworkhour($teacher_id , $year , $month);
    $basic_salary = $num_work_hour * $teacher->cost_hour;
    $solfa = 0;
    $maturitie = $teacher->maturitie;
    foreach($maturitie as $mut)
    {
        $solfa += $mut->amount;
    }
    $salary = $basic_salary - $solfa;


    return response()->json([$teacher,$basic_salary,$salary,$num_work_hour,'successsss']);
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







public function desplay_all_employee_and_others($academy_id)
{
    $academy = Academy::find($academy_id);
    $employee = Employee::where('year',$academy->year)->where('status',1)->get()->all();
    $montor = User::where('user_type','monetor')->where('status',1)->where('year',$academy->year)->get();
    $teacher = User::where('user_type','teacher')->where('status',1)->where('year',$academy->year)->get()->all();

    return response()->json([$employee,$montor,$teacher]);

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


public function desplay_employee()
{
    $employee = Employee::get();
    if(!$employee)
    {
        return response()->json(['you havenot any employee']);
    }
    return response()->json([$employee,'all employee']);

    }
public function desplay_one_employee($employee_id)
{
    $employee = Employee::find($employee_id);
    if(!$employee)
    {
        return response()->json(['you havenot any employee']);
    }
    return response()->json([$employee]);

    }

public function update_employee_profile(Request $request,$employee_id,$academy_id)
{
    $academy = Academy::find($academy_id);
    $employee = Employee::find($employee_id);
    if(!$employee)
    {
        return response()->json(['you have not any employee']);
    }
    $validator = Validator::make($request->all(),[
        'salary' => 'required',
        'type' => 'required',
    ]);
    if ($validator->fails()) {
        return $this->responseError(['errors' => $validator->errors()]);
    }

    $employee ->update([
        'salary' => $request->salary,
        'type' => $request->type,
        'year' => $academy->year,
    ]);

    }

public function getEmployeeAttendance($employee_id, $year, $month)
{
    // انشاء تواريخ البداية والنهاية للشهر المحدد
    $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
    $endDate = $startDate->copy()->endOfMonth();

    // احتساب عدد الأيام الكاملة
    $totalWorkDays = $startDate->diffInDaysFiltered(function (Carbon $date) {
        return !$date->isWeekend();
    }, $endDate);

    // عدد الأيام في الشهر
    $daysInMonth = $startDate->daysInMonth;

    return response()->json([
        'employee_id' => $employee_id,
        'year' => $year,
        'month' => $month,
        'attendance_days' => $daysInMonth, // لأن الموظف يعمل اليوم كاملا
        'total_work_days' => $totalWorkDays
    ]);
    }


public function getempoyeesalary($employee_id)
{
    $employee = Employee::find($employee_id);
    if(!$employee)
    {
        return response()->json(['the employee not found']);
    }

    return $employee->salary;
}



public function add_absence_for_employee($request)
{
    $validator = Validator::make($request->all(), [
        'date' => 'required|date',
        'num_hour_out' => 'required|integer',
        'teacher_id' => 'nullable|exists:teachers,id',
        'employee_id' => 'nullable|exists:employees,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // التأكد من أن أحدهما فقط تم تقديمه
    if (!$request->hasAny(['teacher_id', 'employee_id'])) {
        return response()->json(['message' => 'Either teacher_id or employee_id must be provided'], 422);
    }


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
        // استرجاع برنامج الدوام الأسبوعي الثابت للمعلم مع ربطه بالشعب
        $teacherSchedule = Teacher_Schedule::with('section')
            ->where('teacher_id', $teacher_id)
            ->get();

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
            $isWeekend = in_array($date->format('l'), ['Friday', 'Saturday']);

            if (!$isHoliday && !$isWeekend) {
                // استرجاع جميع الفترات في اليوم الحالي
                $schedules = $teacherSchedule->where('day_of_week', $dayOfWeek);

                $dailySchedule = [];
                $totalWorkingHours = 0;

                foreach ($schedules as $schedule) {
                    // التحقق من وجود بيانات الشعبة
                    $sectionName = $schedule->section ? $schedule->section->num_section	: 'N/A';

                    // حساب عدد ساعات العمل بين وقت البداية ووقت النهاية
                    $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time);
                    $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time);
                    $workingHours = $endTime->diffInHours($startTime);

                    $totalWorkingHours += $workingHours;

                    // إضافة تفاصيل الشعبة مع ساعات العمل
                    $dailySchedule[] = [
                        'section' => $sectionName,
                        'start_time' => $startTime->format('H:i'),
                        'end_time' => $endTime->format('H:i'),
                        'working_hours' => $workingHours,
                    ];
                }
                return $sectionName;

                $attendanceDetails[] = [
                    'date' => $date->format('l d-m-Y'),  // صيغة التاريخ
                    'working_hours' => $totalWorkingHours,
                    'daily_schedule' => $dailySchedule,
                ];
            } else {
                $attendanceDetails[] = [
                    'date' => $date->format('l d-m-Y'),  // صيغة التاريخ
                    'working_hours' => 0, // لا يوجد ساعات عمل في أيام العطل أو نهاية الأسبوع
                    'daily_schedule' => [],
                ];
            }
        }

        return response()->json([
            'teacher_id' => $teacher_id,
            'year' => $year,
            'month' => $month,
            'attendance_details' => $attendanceDetails,
        ]);
    }

public function desplay_section_for_classs($class_id,$year)
{
    $classs = Classs::find($class_id);
    if(!$classs)
    {
        return response()->json(['you havenot any class']);
    }
    return  $classs->section;
}



public function desplay_all_student_regester($year)
{
    $students = User::where('year',$year)->where('user_type', 'student')->get();

    $students = $students->map(function ($student) {
        if ($student->user->image) {
            $student->user->image_file_url = asset('/upload/' . $student->user->image);
        } else {
            $student->user->image_file_url = null; // إذا لم يكن هناك صورة
        }

        return $student;
    });
    return response()->json([$students,'all student regester here']);
}

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
        $student = Student::with('user')->find($student_id);
        if(!$student)
        {
            return response()->json('the student not found ');
        }

        return response()->json(['student' => $student, 'message' => 'Success']);
    }


public function update_profile_student(Request $request,$student_id)
    {
        $student = Student::find($student_id);
        if(!$student)
        {
            return response()->json('the student not found ');
        }
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required|string',
            'father_name' => 'required|string',
            'mother_name' => 'required|string',
            'phone' => 'required',
            'address' => 'required',
            // 'calssification' => 'required',
            'school_tuition'=>'required',
            'class_id'=>'required',
            'section_id'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $user = $student->user;

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->father_name = $request->father_name;
        $user->mother_name = $request->mother_name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user_id = $user->id;
        // $student->calssification = $request->calssification;
        $student->school_tuition = $request->school_tuition;
        $student->user_id = $user_id;
        $student->class_id =$request->class_id;
        $student->section_id= $request->section_id;


        $student->update();
        $user->update();
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
            $justification = null;

            if ($date->format('l') !== 'Friday' && $date->format('l') !== 'Saturday') {
                $absence = Out_Of_Work_Student::where('student_id', $student_id)
                    ->whereDate('date', $date)
                    ->first();

                if ($absence) {
                    $attendanceStatus = 'غائب';
                    $justification = $absence->justification; // استرجاع مبرر الغياب إذا كان موجودًا
                }
            } else {
                $attendanceStatus = 'عطلة';
            }

            $attendanceDetails[] = [
                'date' => $date->toDateString(),
                'attendance_status' => $attendanceStatus,
                'justification' => $justification, // إضافة المبرر إلى التفاصيل
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

public function desplay_student_nots($student_id)
    {
        $student = Student::find($student_id);
        if(!$student)
        {
            return response()->json('the student not found ');
        }

        return response()->json(['student' => $student->note_students, 'message' => 'Success']);

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
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $note_student = new Note_Student();
        $note_student->text = $request->text;
        $note_student->student_id = $student_id;
        $note_student->user_id = auth()->user()->id;

        $note_student->save();

        return response()->json(['successssss']);

}

public function addAbsence(Request $request, $student_id, NotificationController $notificationController)
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

    if ($absence->save()) {
        $title = 'غياب';
        $body = 'يرجى تبرير غياب الطالب ' . $student->user->first_name .' '. $student->user->last_name .' لغيابه يوم '. $absence->date;

        $notificationController->sendNotification_for_parent($title,$body,$student_id);
    }
    

    return response()->json(['message' => 'Absence added successfully'], 200);
}


public function deleteAbsence($student_id, $absence_id)
{
    $student = Student::find($student_id);
    if (!$student) {
        return response()->json(['message' => 'Student not found'], 404);
    }

    $absence = Out_Of_Work_Student::where('student_id', $student_id)->find($absence_id);
    if (!$absence) {
        return response()->json(['message' => 'Absence record not found'], 404);
    }

    // حذف سجل الغياب
    $absence->delete();

    return response()->json(['message' => 'Absence record deleted successfully'], 200);
}

public function updateAbsence_for_student(Request $request, $student_id,$absence_id)
{
    // التحقق من وجود الطالب
    $student = Student::find($student_id);
    if (!$student) {
        return response()->json(['message' => 'Student not found'], 404);
    }
    $absence = Out_Of_Work_Student::where('student_id', $student_id)->find($absence_id);
    if (!$absence) {
        return response()->json(['message' => 'Absence record not found'], 404);
    }
    // التحقق من صحة البيانات المدخلة
    $validator = Validator::make($request->all(), [
        'justification' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // إنشاء سجل الغياب
    $absence->Update([
        'justification'=>$request->justification,
    ]);

    return response()->json(['message' => 'Absence added successfully'], 200);
}


public function add_mark_to_student(Request $request, $student_id)
{
    // القيام بالتحقق من وجود الطالب
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

    if($mark->exam_final == 0)
    {
        $mark->state == 0;
    }
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

public function all_teatcher()
    {
        $teatcher = Teacher::with('user')->get();
        return $teatcher;
    }


// public function desplay_publish()
// {
//     $publish = Publish::get()->all();
//     return response()->json([$publish,'this is all publish']);
// }

public function display_order_for_course($course_id)
{
    $course = Course::find($course_id);
    if(!$course)
    {
        return response()->json(['course not found']);
    }
    $course->order;
    return $course;
}

public function display_details_for_course($course_id)
{
    $course = Course::find($course_id);
    if(!$course)
    {
        return response()->json(['the course not found']);
    }



    return response()->json([$course]);

}
public function display_teacher_in_course($course_id)
{
    $course = Course::find($course_id);
    if(!$course)
    {
        return response()->json(['the course not found']);
    }
    $teacher = $course->teacher;
    // return $teacher;
    $teacher1 = $teacher->user;
    foreach($teacher1 as $teachers){
    echo $teacher1->first_name ." " .$teacher1->last_name;
    }
}

public function display_subject_in_course($course_id)
{
    $course = Course::find($course_id);
    if(!$course)
    {
        return response()->json(['the course not found']);
    }
    $subject = $course->subject;
    foreach($subject as $subject)
    {
        echo $subject->name;
    }
}

// public function add_publish(Request $request)
// {
//     $validator = Validator::make($request->all(),[
//         'description'=>'required|string',
//         'course_id'=>'required',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()]);
//         }

//         $publish = new Publish();
//         $publish->description = $request->description;
//         $publish->course_id = $request->course_id;
//         $publish->save();
//         return response()->json(['sucssscceccs']);
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

// public function update_publish(Request $request,$publish_id)
// {
//     $publish = Publish::find($publish_id);
//     if(!$publish)
//     {
//         return response()->json(['the publish not found']);
//     }
//     $validator = Validator::make($request->all(),[
//         'description'=>'required|string',
//         //'course_id'=>'required',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['errors' => $validator->errors()]);
//         }

//         if ($request->has('description')) {
//             $publish->description = $request->description;
//         }

//         $image = Image::where('publish_id', $publish_id)->first();

//         if (!$image) {
//             $validator = Validator::make($request->all(),[
//                 'path' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt'
//             ]);

//             if ($validator->fails()) {
//                 return response()->json([
//                     'status' => 'false',
//                     'message' => 'Please fix the errors',
//                     'errors' => $validator->errors()
//                 ]);
//             }

//             $img = $request->path;
//             $ext = $img->getClientOriginalExtension();
//             $imageName = time().'.'.$ext;
//             $img->move(public_path().'/upload',$imageName);

//             $image = new Image;
//             $image->path = $imageName;
//             $image->publish_id = $publish_id;
//             $image->description = $request->description ?? $publish->description;
//             $image->save();

//             return response()->json([
//                 'status' => 'true',
//                 'message' => 'image upload success',
//                 'path' => asset('/upload/'.$imageName),
//                 'data' => $image
//             ]);
//         }

//         // حذف الصورة القديمة من المجلد إذا كانت موجودة
//         $oldImagePath = public_path().'/upload/'.$image->path;
//         if (file_exists($oldImagePath)) {
//             unlink($oldImagePath);
//         }

//         // رفع الصورة الجديدة
//         $img = $request->path;
//         $ext = $img->getClientOriginalExtension();
//         $imageName = time().'.'.$ext;
//         $img->move(public_path().'/upload', $imageName);

//         // تحديث مسار الصورة في قاعدة البيانات
//         $image->path = $imageName;

//         if ($request->has('description')) {
//             $image->description = $request->description;
//         }
//         $image->save();

//         return response()->json([
//             'status' => 'true',
//             'message' => 'Image updated successfully',
//             'path' => asset('/upload/'.$imageName),
//             'data' => $image
//         ]);


//         // $publish->course_id = $request->course_id;
//         $publish->save();
//         return response()->json(['sucssscceccs']);
// }

public function update_publish(Request $request, $publish_id)
{
    // البحث عن الكائن المحدد
    $publish = Publish::find($publish_id);
    if (!$publish) {
        return response()->json(['message' => 'The publish not found']);
    }

    // التحقق من صحة المدخلات
    $validator = Validator::make($request->all(), [
        'description' => 'nullable|string',
        //'course_id' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()]);
    }

    // تحديث الوصف في الكائن Publish إذا كان موجودًا في الطلب
    if ($request->has('description') && !empty($request->description)) {
        $publish->description = $request->description;
    }

    // البحث عن الصورة المرتبطة بالكائن
    $image = Image::where('publish_id', $publish_id)->first();

    if ($request->hasFile('path')) {
        // التحقق من صحة الصورة المرفوعة
        $validator = Validator::make($request->all(), [
            'path' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'Please fix the errors',
                'errors' => $validator->errors()
            ]);
        }

        if ($image) {
            // حذف الصورة القديمة من المجلد إذا كانت موجودة
            $oldImagePath = public_path() . '/upload/' . $image->path;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        } else {
            // إنشاء كائن جديد للصورة إذا لم يكن موجودًا
            $image = new Image;
            $image->publish_id = $publish_id;
        }

        // رفع الصورة الجديدة
        $img = $request->file('path');
        $ext = $img->getClientOriginalExtension();
        $imageName = time() . '.' . $ext;
        $img->move(public_path('/upload'), $imageName);

        // تحديث مسار الصورة في قاعدة البيانات
        $image->path = $imageName;
    }

    // تحديث الوصف في كائن Image إذا كان موجودًا في الطلب
    if ($request->has('description') && $image) {
        $image->description = $request->description;
    }

    // حفظ التغييرات في كائن Image
    if ($image) {
        $image->save();
    }

    // حفظ التحديثات في الكائن Publish
    $publish->save();

    return response()->json([
        'status' => 'true',
        'message' => 'Publish and image updated successfully',
        'publish' => $publish,
        'image' => $image,
        'path' => isset($imageName) ? asset('/upload/' . $imageName) : null
    ]);
}




public function add_to_expensess(Request $request)
{
    $academy = Academy::find(1);
    $validator = Validator::make($request->all(),[
        'date' => 'required|date',
        'product'=>'required',
        'cost_one_piece'=>'required',
        'num_product'=>'required',
    ]);

    if ($validator->fails()) {
        return $this->responseError(['errors' => $validator->errors()]);
    }

    $expenses = new Expenses();
    $expenses->date = $request->date;
    $expenses->product = $request->product;
    $expenses->cost_one_piece = $request->cost_one_piece;
    $expenses->num_product = $request->num_product;
    $expenses->total_cost = $request->num_product * $request->cost_one_piece;
    $expenses->year = $academy->year;
    $expenses->save();
    return response()->json(['sucsseesss']);

}


public function add_to_break(Request $request,$academy_id)
{
    $academy = Academy::find($academy_id);
    $validator = validator::make($request->all(),[
        'first_name'=>'required',
        'last_name'=>'required',
        'phone'=>'required',
        'address'=>'required',
        'cost_from_breake'=>'required',
    ]);

    if ($validator->fails()) {
        return $this->responseError(['errors' => $validator->errors()]);
    }

    $break = new Breake();
    $break->first_name = $request->first_name;
    $break->last_name = $request->last_name;
    $break->phone = $request->phone;
    $break->address = $request->address;
    $break->year = $academy->year;
    $break->cost_from_breake = $request->cost_from_breake;

    $break->save();

}



// public function add_mark_to_student(request $request,$student_id)
// {
//     $student = Student::find($student_id);
//     if(!$student)
//     {
//         return response()->json(['the student not found']);
//     }

//     $mark = new Mark;
//     $mark->ponus = $request->ponus  ;
//     $mark->homework = $request->homework || null;
//     $mark->oral = $request->oral || null;
//     $mark->test1 = $request->test1 || null;
//     $mark->test2 = $request->test2 || null;
//     $mark->exam_med = $request->exam_med || null;
//     $mark->exam_final = $request->exam_final || null;
//     $aggregrate = ($request->ponus + $request->homework
//     + $request->oral + $request->test1
//     +$request->test2 + $request->exam_med
//     +$request->exam_final);
//     if ($aggregrate > 50){
//     $mark->state = 1;
//     }
//     else {
//         $mark->state = 0;
//     }
//     $mark->student_id = $student_id;
//     $mark->subject_id = $request->subject_id;
//     $mark->save();

//     return response()->json(['succusssss']);

// }


public function calculateMonthlySalary($teacher_id, $year, $month)
{
    // استرجاع برنامج الدوام الأسبوعي للأستاذ
    $teacherSchedule = Teacher_Schedule::where('teacher_id', $teacher_id)->get();

    // استرجاع قائمة أيام العطل والغيابات في الشهر
    $holidays = Out_Of_Work_Employee::where('teacher_id', $teacher_id)
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->pluck('date')->toArray();

    // حساب عدد الأيام في الشهر
    $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

    // استرجاع أجر الساعة للأستاذ
    $teacher = Teacher::findOrFail($teacher_id);
    $hourlyRate = $teacher->hourly_rate;

    $totalWorkingHours = 0;

    // حساب عدد ساعات العمل في الشهر
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = Carbon::createFromDate($year, $month, $day);
        $dayOfWeek = $date->format('l');

        if (in_array($date->toDateString(), $holidays) || in_array($dayOfWeek, ['Friday', 'Saturday'])) {
            continue; // تخطي أيام العطل والغيابات
        }

        foreach ($teacherSchedule as $schedule) {
            if ($schedule->day_of_week == $dayOfWeek) {
                $workingHours = $this->getWorkingHoursForDays($schedule);
                $totalWorkingHours += $workingHours;
            }
        }
    }
    $teacher = Teacher::find($teacher_id);
    $hourlyRate = $teacher->cost_hour ;
    $addedHour = $teacher->num_hour_added ;
    // حساب الراتب الشهري
    $monthlySalary = ($totalWorkingHours + $addedHour) * $hourlyRate;

    return response()->json([
        'teacher_id' => $teacher_id,
        'year' => $year,
        'month' => $month,
        'total_working_hours' => $totalWorkingHours,
        'monthly_salary' => $monthlySalary,
    ]);
}

private function getWorkingHoursForDays($schedule)
{
    // حساب عدد ساعات العمل بين وقت البداية ووقت النهاية
    $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time);
    $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time);
    $workingHours = $endTime->diffInHours($startTime);
    return $workingHours;
}

//تعديل معلومات المعهد
public function edit_info_academy(Request $request,$id)
{
    $info = Academy::find($id);

    $info->name = $request->name ?? $info->name;
    $info->phone = $request->phone ?? $info->phone;
    $info->address = $request->address ?? $info->address;
    $info->facebook_link = $request->facebook_link ?? $info->facebook_link;
    $info->description = $request->description ?? $info->description;
    $info->year = $request->year ?? $info->year;

    $info->save();

    return $info;
}

//تعديل السنة الدراسية
// public function edit_year(Request $request,$id)
// {
//     // $info = Academy::find($id);

//     // $info->year = $request->year ?? $info->year;

//     // $info->name = $info->name;
//     // $info->phone = $info->phone;
//     // $info->address = $info->address;
//     // $info->facebook_link = $info->facebook_link;
//     // $info->description = $info->description;

//     // // if ($request->has('year')) {
//     // //     $info->year = $request->year ;
//     // // }
//     // $info->save();

//     // return $info;
//     $info = Academy::find($id);

//     // $info->name = $request->name ?? $info->name;
//     // $info->phone = $request->phone ?? $info->phone;
//     // $info->address = $request->address ?? $info->address;
//     // $info->facebook_link = $request->facebook_link ?? $info->facebook_link;
//     $info->year = $request->year ?? $info->year;

//     $info->save();

//     return $info;
// }





public function student_course($student_id)
    {
        // $student = Student::where('user_id', auth()->user()->id)->first();
        // if (!$student) {
        //     return response()->json(['error' => 'Student not found'], 404);
        // }
        $order = Order::where('student_id', $student_id)->with('course.teacher.user')->get();

        return $order;
    }



public function addMaturitie(Request $request, NotificationController $notificationController)
{
    $validator = Validator::make($request->all(), [
        'amount'=>'required',
        'teacher_id' => 'nullable|exists:teachers,id',
        'employee_id' => 'nullable|exists:employees,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // التأكد من أن أحدهما فقط تم تقديمه
    if (!$request->hasAny(['teacher_id', 'employee_id'])) {
        return response()->json(['message' => 'Either teacher_id or employee_id must be provided'], 422);
    }


    $maturite = new Maturitie();
    $maturite->amount = $request->amount;
    $maturite->teacher_id = $request->teacher_id;
    $maturite->employee_id = $request->employee_id;
    // $maturite->save();

    if ($maturite->save()) {
        $title = 'سلفة';
        $body ='تم إضافة سلفة لكم مقدارها '. $maturite->amount;

        $teacher =Teacher::find($maturite->teacher_id);
        
        $notificationController->sendNotification_call($teacher->user->fcm_token, $title, $body_s);
    }

    return response()->json(['sucssssss']);

    }



public function deleteMaturitie(Request $request,$mut_id)
{

    $validator = Validator::make($request->all(), [
        'teacher_id' => 'nullable|exists:teachers,id',
        'employee_id' => 'nullable|exists:employees,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // if (!$request->hasAny(['teacher_id', 'employee_id'])) {
    //     return response()->json(['message' => 'Either teacher_id or employee_id must be provided'], 422);
    // }

    if($request->has('teacher_id'))
    {
        $teacher = Teacher::find($request->teacher_id);
        if($teacher)
        {
            $mut = Maturitie::find($mut_id);

            if($mut)
            {
                $mut->delete;
            }
        }
    }

    if($request->has('employee_id'))
    {
        $employee = Employee::find($request->employee_id);
        if($employee)
        {
            $mut = Maturitie::find($mut_id)->where('employee_id',$request->employee_id);
            if($mut)
            {
                $mut->delete();
            }
        }
    }


    return response()->json(['sucssssss']);
    }


public function Add_course(Request $request,$academy_id)
{
    $academy = Academy::find($academy_id);
    $validator = Validator::make($request->all(), [
        'name_course'=>'required',
        'description'=>'required',
        'cost_course'=>'required',
        'start_date'=>'required',
        'finish_date'=>'required',
        'start_time'=>'required',
        'finish_time'=>'required',
        'percent_teacher'=>'required',
        'subject_id'=>'required',
        'class_id'=>'required',
        'teacher_id'=>'required',
        'name_file' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt',
        'description_file'=>'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }


    $course = new Course();
    $course->name_course = $request->name_course;
    $course->description = $request->description;
    $course->cost_course = $request->cost_course;
    $course->start_date = $request->start_date;
    $course->finish_date = $request->finish_date;
    $course->start_time = $request->start_time;
    $course->finish_time = $request->finish_time;
    $course->year = $academy->year;
    $course->percent_teacher = $request->percent_teacher;
    $course->subject_id = $request->subject_id;
    $course->class_id = $request->class_id;
    $course->teacher_id = $request->teacher_id;


    $course->save();

    $img = $request->name_file;
    $ext = $img->getClientOriginalExtension();
    $imgFileName = time().'.'.$ext;
    $img->move(public_path().'/upload',$imgFileName);

    if ($ext=="png" || $ext=="jpg" || $ext=="jpeg" || $ext=="gif" ||
    $ext=="pdf" || $ext=="docx" || $ext=="txt") {
    $image = new File_course();
    $image->name = $imgFileName;
    $image->description = $request->description_file;

    $image->course_id = $course->id;

    $image->save();

    }

    return response()->json(['addedddd   course  with files']);


    }



public function upload_file_image_for_course(Request $request, $course_id, NotificationController $notificationController)
{
    $validator = Validator::make($request->all(),[
        'name' => 'required|mimes:png,jpg,jpeg,gif,pdf,docx,txt'
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

    // if ($ext=="png" || $ext=="jpg" || $ext=="jpeg" || $ext=="gif") {
        $image = new File_course();
    $image->name = $imgFileName;
    $image->description = $request->description;
    $image->course_id = $request->course_id;
    // $image->save();

    if ($image->save()) {
        $course = Course::find($course_id);
        $title = 'ملفات جديدة للدورة '.$course->name_course;
        $body = $image->description;
        
        $notificationController->sendNotification_call($course->teacher->user->fcm_token, $title, $body_s);
        $notificationController->sendNotification_all_student_course($title,$body,$course_id);
    }

    return response()->json([
        'status' => 'true',
        'message' => 'image_file upload success',
        'path' => asset('/upload/'.$imgFileName),
        'data' => $image
    ]);
    // }

    // elseif ($ext=="pdf" || $ext=="docx" || $ext=="txt") {
    //     $file = new File_course();
    // $file->name = $imgFileName;
    // $file->description = $request->description;
    // $file->course_id = $request->course_id;
    // $file->save();

    // return response()->json([
    //     'status' => 'true',
    //     'message' => 'file upload success',
    //     'path' => asset('/upload/'.$imgFileName),
    //     'data' => $file
    // ]);
    // }

}
public function desplay_all_publish()
    {
        $publish = Publish::with('course')->get()->all();
        return response()->json([$publish,'this is all publish']);
    }

public function desplay_publish($publish_id)
{
    $publish = Publish::find($publish_id);
    if(!$publish)
    {
        return response()->json(['the publish not found']);
    }
    $publish->course;
    $publish->image;
    return response()->json([$publish]);


}

public function desplay_section_and_student($class_id)
{
    $classs = Classs::find($class_id);
    if(!$classs)
    {
        return response()->json(['the classs not found']);
    }
    $section = $classs->section;
    $student  =  Section::with('student')->find($section);
    $user =  Student::with('user')->find($student);



    return response()->json([$classs,$student,$user]);

}

public function add_section(Request $request, $class_id)
{
    $validator = Validator::make($request->all(),[
        'num_section'=>'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'false',
            'message' => 'Please fix the errors',
            'errors' => $validator->errors()
        ]);
    }
    $section = new Section();
    $section->num_section= $request->num_section;
    $section->class_id = $class_id;
    $section->save();
    return response()->json(['sucsssss']);
}

public function desplay_maturitie_for_teacher($teacher_id, $year, $month)
{
    $teacher = Teacher::find($teacher_id);
    if (!$teacher) {
        return response()->json(['the teacher not found']);
    }

    // حساب عدد ساعات العمل والراتب الأساسي
    $num_work_hour = $this->getteacherworkhour($teacher_id, $year, $month);
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

// public function desplay_maturitie_for_employee($employee_id, $year, $month)
// {
//     $employee = Employee::find($employee_id);
//     if (!$employee) {
//         return response()->json(['the employee not found']);
//     }

//     // الحصول على الراتب الأساسي للموظف
//     $basic_salary = $employee->salary;

//     // الحصول على السلف لهذا الشهر فقط
//     $solfa = 0;
//     $maturities = $employee->maturitie()
//         ->whereYear('updated_at', $year)
//         ->whereMonth('updated_at', $month)
//         ->get();

//     foreach ($maturities as $mut) {
//         $solfa += $mut->amount;
//     }

//     $salary = $basic_salary - $solfa;

//     return response()->json([
//         'basic_salary' => $basic_salary,
//         'maturities' => $maturities,
//         'total_solfa' => $solfa,
//         'remaining_salary' => $salary
//     ]);
// }

public function desplay_maturitie_for_employee($employee_id,$year,$month)
{
    $employee = Employee::find($employee_id);
    if(!$employee)
    {
        return response()->json(['the employee not found']);
    }
    $basic_salary = $employee->salary;
    $solfa = 0;
    $maturities = $employee->maturitie()
    ->whereYear('updated_at', $year)
    ->whereMonth('updated_at', $month)
    ->get();
    foreach($maturities as $mut)
    {
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

public function add_marks_to_section(Request $request, $section_id)
    {
        $section = Section::find($section_id);
        if (!$section) {
            return response()->json(['error' => 'Section not found'], 404);
        }

        // التحقق من صحة البيانات المرسلة
        $validator = Validator::make($request->all(), [
            'mark_type' => 'required|in:ponus,homework,oral,test1,test2,exam_med,exam_final',
            'subject_id' => 'required|exists:subjects,id',  // التحقق من وجود المادة
            'marks' => 'required|array',
            'marks.*.student_id' => 'required|exists:students,id',  // التحقق من وجود الطالب
            'marks.*.value' => 'required|numeric',  // قيمة العلامة للجزء المحدد
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $subject_id = $request->input('subject_id');
        if (!$subject_id) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        foreach ($request->marks as $markData) {
            // البحث عن العلامة الموجودة للطالب في المادة
            $mark = Mark::where('student_id', $markData['student_id'])
                        ->where('subject_id', $subject_id)
                        ->first();

            if (!$mark) {
                // إذا لم يكن هناك علامة سابقة، قم بإنشاء علامة جديدة
                $mark = new Mark;
                $mark->student_id = $markData['student_id'];
                $mark->subject_id = $subject_id;
            }

            // تحديث الجزء المحدد من العلامة فقط
            $mark_type = $request->input('mark_type');
            switch ($mark_type) {
                case 'ponus':
                    $mark->ponus = $markData['value'];
                    break;
                case 'homework':
                    $mark->homework = $markData['value'];
                    break;
                case 'oral':
                    $mark->oral = $markData['value'];
                    break;
                case 'test1':
                    $mark->test1 = $markData['value'];
                    break;
                case 'test2':
                    $mark->test2 = $markData['value'];
                    break;
                case 'exam_med':
                    $mark->exam_med = $markData['value'];
                    break;
                case 'exam_final':
                    $mark->exam_final = $markData['value'];
                    break;
                default:
                    return response()->json(['error' => 'Invalid mark type'], 400);
            }

            // التحقق من وجود exam_final قبل حساب المحصلة النهائية وتحديد حالة الطالب
            if ($mark->exam_final !== null) {
                $finalScore  =  $mark->ponus + $mark->homework + $mark->oral
                                + $mark->test1 + $mark->test2 + $mark->exam_med + $mark->exam_final;

                $mark->state = ($finalScore >= 50) ? 1 : 0;
            } else {
                // إذا لم تكن علامة الامتحان النهائي موجودة، اجعل حقل state null
                $mark->state = null;
            }

            // حفظ العلامة
            // $mark->save();

            if ($mark->save()) {
                $subject = Subject::find($mark->subject_id);
                $title = 'علامة جديدة';
                // $body ='علامة جديدة في مادة '. $subject->name.' للطالب '. $student->user->first_name .' '. $student->user->last_name;
                $body = 'علامة جديدة في مادة ' . $subject->name;
                
                $notificationController->sendNotification_for_parent($title,$body,$student_id);
                $notificationController->sendNotification_student_section($title,$body,$section_id);
        }
        }

        return response()->json(['success' => 'Marks updated successfully for all students in the section']);
}

public function calculateStudentMarks(Request $request, $student_id, NotificationController $notificationController)
{
    // تحقق من وجود الطالب
    $student = Student::find($student_id);
    if (!$student) {
        return response()->json(['error' => 'Student not found'], 404);
    }

    // تحقق من صحة المدخلات
    $validator = Validator::make($request->all(), [
        'subject_id' => 'required|exists:subjects,id', // تأكد من أن subject_id موجودة في جدول subjects
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // البحث عن السجل في جدول marks
    $mark = Mark::where('student_id', $student_id)
                ->where('subject_id', $request->input('subject_id'))
                ->first();

    if (!$mark) {
        return response()->json(['error' => 'Mark record not found'], 404);
    }

    if ($mark->exam_final !== null) {
        $finalScore  =  $mark->ponus + $mark->homework + $mark->oral
                        + $mark->test1 + $mark->test2 + $mark->exam_med + $mark->exam_final;

        $mark->state = ($finalScore >= 50) ? 1 : 0;
    } else {
        // إذا لم تكن علامة الامتحان النهائي موجودة، اجعل حقل state null
        $mark->state = null;
    }

    // حفظ النتيجة في قاعدة البيانات
    // $mark->save();

    if ($mark->save() && $mark->state != null) {

        $subject = Subject::find($request->input('subject_id'));
        $title = 'محصلة';
        if ($mark->state == '1') {
            $sate = 'ناجح';
        }
        else {
            $sate = 'راسب';
        }
        $body ='محصلة علامات مادة '. $subject->name.' للطالب '. $student->user->first_name .' '. $student->user->last_name .' هي '. $sate;
        $body_s ='محصلة علامات مادة ' . $subject->name . ' هي '.$sate;
        
        $notificationController->sendNotification_for_parent($title,$body,$student_id);
        $notificationController->sendNotification_call($student->user->fcm_token, $title, $body_s);


    }

    // إرجاع النتيجة النهائية والحالة
    return response()->json(['final_score' => $finalScore, 'state' => $mark->state]);
}


public function calculateAllStudentMarks($student_id)
    {
    // تحقق من وجود الطالب
    $student = Student::find($student_id);
    if (!$student) {
        return response()->json(['error' => 'Student not found'], 404);
    }

    // جلب جميع السجلات في جدول marks التي تخص الطالب
    $marks = Mark::where('student_id', $student_id)->get();

    if ($marks->isEmpty()) {
        return response()->json(['error' => 'No marks found for the student'], 404);
    }

    $results = [];

    foreach ($marks as $mark) {
        if ($mark->exam_final !== null) {
            // حساب النتيجة النهائية
            $finalScore  =  $mark->ponus + $mark->homework + $mark->oral
                            + $mark->test1 + $mark->test2 + $mark->exam_med + $mark->exam_final;

            // تحديد حالة النجاح أو الرسوب
            $mark->state = ($finalScore >= 50) ? 1 : 0;
        } else {
            // إذا لم تكن علامة الامتحان النهائي موجودة، اجعل حقل state null
            $mark->state = null;
            $finalScore = null; // لا يمكن حساب النتيجة النهائية
        }

        // حفظ النتيجة في قاعدة البيانات
        $mark->save();

        // تخزين النتائج في مصفوفة النتائج
        $results[] = [
            'subject_id' => $mark->subject_id,
            'final_score' => $finalScore,
            'state' => $mark->state
        ];
    }

    // إرجاع النتيجة النهائية والحالة لكل مادة
    return response()->json(['results' => $results]);
}

public function add_extrahour($teacher_id, Request $request, NotificationController $notificationController)
    {
        // التحقق من صحة البيانات المدخلة
        $validatedData = $request->validate([
            'num_hour_added' => 'required|integer|min:1',
            'note_hour_added' => 'nullable|string',
        ]);

        // التحقق من وجود المدرس
        $teacher = Teacher::find($teacher_id);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        // إضافة الساعات الإضافية
        $hourAdded = Hour_Added::create([
            'teacher_id' => $teacher_id,
            'num_hour_added' => $validatedData['num_hour_added'],
            'note_hour_added' => $validatedData['note_hour_added'],
        ]);


        $title = 'ساعات إضافية';
        $body_t = 'تم إضافة '. $validatedData['num_hour_added'] . ' ساعات إضافية '.$validatedData['note_hour_added'].' لك';
        $body_a = 'تم إضافة '. $validatedData['num_hour_added'] . ' ساعات إضافية للأستاذ'. $teacher->user->first_name.' '.$teacher->user->last_name.' '.$validatedData['note_hour_added'];

        $notificationController->sendNotification_call($teacher->user->fcm_token, $title, $body_t);
        $notificationController->sendNotification_for_all_admin($title,$body_a);

        return response()->json([
            'message' => 'Extra hours added successfully',
            'hour_added' => $hourAdded
        ], 200);
    }


public function update_extrahour($teacher_id, $id, Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validatedData = $request->validate([
            'num_hour_added' => 'required|integer|min:1',
            'note_hour_added' => 'nullable|string',
        ]);

        // التحقق من وجود المدرس
        $teacher = Teacher::find($teacher_id);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        // التحقق من وجود الساعات الإضافية
        $hourAdded = Hour_Added::where('id', $id)
                ->where('teacher_id', $teacher_id)
                ->first();

        if (!$hourAdded) {
            return response()->json(['message' => 'Hour record not found or does not belong to this teacher'], 404);
        }

        // تحديث السجل
        $hourAdded->update([
            'num_hour_added' => $validatedData['num_hour_added'],
            'note_hour_added' => $validatedData['note_hour_added'],
        ]);

        return response()->json([
            'message' => 'Extra hours updated successfully',
            'hour_added' => $hourAdded
        ], 200);
    }

public function delete_extrahour($teacher_id, $hour_id)
    {
        // التحقق من وجود المدرس
        $teacher = Teacher::find($teacher_id);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        // التحقق من وجود الساعات الإضافية
        $hourAdded = Hour_Added::where('id', $hour_id)
                    ->where('teacher_id', $teacher_id)
                    ->first();

        if (!$hourAdded) {
            return response()->json(['message' => 'Hour record not found or does not belong to this teacher'], 404);
        }

        // حذف السجل
        $hourAdded->delete();

        return response()->json(['message' => 'Extra hours deleted successfully'], 200);
    }


public function deleteAbsenceforteacher($teacher_id, $absence_id)
    {
        $teacher = Teacher::find($teacher_id);
        if (!$teacher) {
            return response()->json(['message' => 'teacher not found'], 404);
        }

        $absence = Out_Of_Work_Employee::where('teacher_id', $teacher_id)->find($absence_id);
        if (!$absence) {
            return response()->json(['message' => 'Absence not found in this day'], 404);
        }

        // حذف سجل الغياب
        $absence->delete();

        return response()->json(['message' => 'Absence record deleted successfully'], 200);
    }

public function updatenoteforabsence_for_teacher(Request $request,$teacher_id,$absence_id)
    {
        $teacher = Teacher::find($teacher_id);
        if (!$teacher) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $absence = Out_Of_Work_Employee::find($absence_id);
        if (!$absence) {
            return response()->json(['message' => 'Absence not found in this day'], 404);
        }

        $validator = Validator::make($request->all(), [
            'note' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseError(['errors' => $validator->errors()]);
        }


        $absence->update([
            'note'=>$request->note,
        ]);

        return response()->json(['updated successfuly']);


    }
public function getTeacherExtraHours(Request $request,$teacher_id)
    {

        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseError(['errors' => $validator->errors()]);
        }
        $teacher = Teacher::find($teacher_id);
        if(!$teacher)
        {
            return response()->json(['the teacher not found']);
        }
        $month = $request->month;

        $totalHours = Hour_Added::getTeacherHoursForMonth($teacher_id, $month);
        $hoursDetails = Hour_Added::where('teacher_id', $teacher_id)
        ->whereMonth('created_at', $month)
        ->get();

        return response()->json([
            'teacher_id' => $teacher->id,
            'month' => $month,
            'total_hours' => $totalHours,
            'hours_details' => $hoursDetails,

        ]);
    }

    public function getTeacherOutOfWorkHour(Request $request,$teacher_id)
    {

        $validator = Validator::make($request->all(), [
            'month' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseError(['errors' => $validator->errors()]);
        }
        $teacher = Teacher::find($teacher_id);
        if(!$teacher)
        {
            return response()->json(['the teacher not found']);
        }
        $month = $request->month;

        $totalHours = Out_Of_Work_Employee::totalHoursOutOfWork($teacher_id, $month);
        $hoursDetails = Out_Of_Work_Employee::where('teacher_id', $teacher_id)
        ->whereMonth('date', $month)
        ->get();

        return response()->json([
            'teacher_id' => $teacher->id,
            'month' => $month,
            'total_hours' => $totalHours,
            'hours_details' => $hoursDetails,

        ]);
    }

public function getStudentResult($student_id, $subject_id)
    {
        // التحقق من صحة المعلمات المستلمة
        $student = Student::find($student_id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $subject = Subject::find($subject_id);
        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        // البحث عن العلامات الخاصة بالطالب والمادة
        $mark = Mark::where('student_id', $student_id)
                    ->where('subject_id', $subject_id)
                    ->first();

        if (!$mark) {
            return response()->json(['error' => 'Marks not found for the given student and subject'], 404);
        }

        // حساب المجموع الكلي
        $aggregate = ($mark->homework ?? 0) + ($mark->oral ?? 0) +
        ($mark->test1 ?? 0) + ($mark->test2 ?? 0) + ($mark->exam_med ?? 0) +
        ($mark->exam_final ?? 0);

        // التحقق من إضافة الـ ponus
        if ($mark->ponus !== null && $mark->ponus > 0) {
        $remainingToHundred = 100 - $aggregate;

        // إذا كانت المحصلة الحالية أقل من 100، أضف الجزء المناسب من الـ ponus
        if ($remainingToHundred > 0) {
        $mark->ponus = min($mark->ponus, $remainingToHundred);
        $aggregate += $mark->ponus;

        // تحديد حالة الطالب (ناجح/راسب)
        $isPassed = ($aggregate > 50 && $mark->exam_final != 0) ? true : false;

        return response()->json([
            'student_id' => $student_id,
            'subject_id' => $subject_id,
            'total_marks' => $aggregate,
            'is_passed' => $isPassed ? 'Passed' : 'Failed',
        ]);
            }
        }
    }

public function get_state($student_id, $subject_id)
    {
        // التحقق من صحة المعلمات المستلمة
        $student = Student::find($student_id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $subject = Subject::find($subject_id);
        if (!$subject) {
            return response()->json(['error' => 'Subject not found'], 404);
        }

        // البحث عن العلامات الخاصة بالطالب والمادة
        $mark = Mark::where('student_id', $student_id)
                    ->where('subject_id', $subject_id)
                    ->first();

        if (!$mark) {
            return response()->json(['error' => 'Marks not found for the given student and subject'], 404);
        }
        $isPassed = ($mark->state == 1) ? true : false;
        return response()->json(['is_passed' => $isPassed ? 'Passed' : 'Failed']);
}



public function getStudentOverallResult($student_id)
    {
        // التحقق من وجود الطالب
        $student = Student::find($student_id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // جلب جميع العلامات الخاصة بالطالب
        $marks = Mark::where('student_id', $student_id)->get();

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
            'student_id' => $student_id,
            'overall_result' => $isPassed ? 'Passed' : 'Failed',
            'subjects' => $subjectDetails,
        ]);
    }


}

