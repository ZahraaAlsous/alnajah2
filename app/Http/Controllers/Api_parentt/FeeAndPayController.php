<?php

namespace App\Http\Controllers\Api_parentt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Pay_Fee;

class FeeAndPayController extends Controller
{

    public function fee($student_id)
    {
      $pay = Pay_Fee::where('student_id', $student_id)->with('student')->get();
      // $pay = Pay_Fee::where('student_id', $student_id)->get();
      // حساب المبلغ المتبقي للطالب
      $total_paid = $pay->sum('amount_money');
      //$total_fee1 = Student::where('id', $student_id)->first('school_tuition');
      $total_fee = Student::where('id', $student_id)->value('school_tuition');
      $remaining_fee = $total_fee - $total_paid;

      return ['payments' => $pay, 'remaining_fee' => $remaining_fee];

    }

}
