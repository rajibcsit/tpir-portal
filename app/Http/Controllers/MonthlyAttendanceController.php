<?php
namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
class MonthlyAttendanceController extends Controller {
    public function index(Request $request) {
        $month = $request->input('month', now()->format('Y-m'));
        try { $start = Carbon::createFromFormat('Y-m-d', $month.'-01')->startOfMonth(); }
        catch (\Throwable $e) { abort(422, 'Invalid month'); }
        $end = $start->copy()->endOfMonth();
        $q = Student::where('status','Active')->withCount([
            'attendances as present_days' => fn($a) => $a->whereBetween('attendance_date',[$start->toDateString(),$end->toDateString()])->where('status','Present'),
            'attendances as absent_days' => fn($a) => $a->whereBetween('attendance_date',[$start->toDateString(),$end->toDateString()])->where('status','Absent'),
            'attendances as late_days' => fn($a) => $a->whereBetween('attendance_date',[$start->toDateString(),$end->toDateString()])->where('status','Late'),
            'attendances as leave_days' => fn($a) => $a->whereBetween('attendance_date',[$start->toDateString(),$end->toDateString()])->where('status','Leave'),
        ]);
        if ($request->filled('department')) $q->where('department',$request->department);
        if ($request->filled('technology')) $q->where('technology',$request->technology);
        $students = $q->orderBy('department')->orderBy('technology')->orderBy('student_id')->get()->map(function($s) {
            $counted = $s->present_days + $s->absent_days + $s->late_days;
            $s->attendance_percentage = $counted ? round(($s->present_days / $counted) * 100, 2) : null;
            return $s;
        });
        $departments = Student::select('department')->distinct()->orderBy('department')->pluck('department');
        $technologies = Student::whereNotNull('technology')->select('technology')->distinct()->orderBy('technology')->pluck('technology');
        return view('attendance.monthly',compact('month','students','departments','technologies'));
    }
}
