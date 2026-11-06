<?php
namespace App\Http\Controllers;
use App\Models\Student; use Illuminate\Http\Request;
class StudentPortalController extends Controller {
 public function index(Request $request){$student=Student::with(['attendances'=>fn($q)=>$q->latest('attendance_date')->take(12),'examResults'=>fn($q)=>$q->latest()->take(20)])->findOrFail($request->session()->get('portal_user.student_id')); $total=$student->attendances()->whereIn('status',['Present','Absent','Late'])->count(); $present=$student->attendances()->whereIn('status',['Present','Late'])->count(); $attendancePercent=$total?round($present/$total*100,1):0; return view('student-portal.dashboard',compact('student','attendancePercent'));}
}
