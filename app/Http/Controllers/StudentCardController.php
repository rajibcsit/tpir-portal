<?php
namespace App\Http\Controllers;
use App\Models\Student; use Illuminate\Http\Request;
class StudentCardController extends Controller {
 public function show(Student $student){return view('id-cards.show',compact('student'));}
 public function mine(Request $request){$student=Student::findOrFail($request->session()->get('portal_user.student_id'));return view('id-cards.show',compact('student'));}
}
