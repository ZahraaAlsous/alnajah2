<?php

namespace App\Http\Controllers\Api_student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mark;
use App\Models\Student;

class MarkController extends Controller
{
    public function displayMark()
    {
        $student = Student::where('user_id',auth()->user()->id)->first();
        $mark = Mark::where('student_id', $student->id)->with('subject')->get();

        return $mark;
    }

}
