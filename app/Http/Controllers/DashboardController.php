<?php
namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Payment;

class DashboardController extends Controller {
    public function index() {
        $today = now()->toDateString();
        return view('dashboard', [
            'studentCount'=>Student::count(),
            'activeCount'=>Student::where('status','Active')->count(),
            'todayPresent'=>Attendance::whereDate('attendance_date',$today)->where('status','Present')->count(),
            'todayAbsent'=>Attendance::whereDate('attendance_date',$today)->where('status','Absent')->count(),
            'totalDue'=>max(0,(float)Payment::sum('amount_due')-(float)Payment::sum('amount_paid')),
            'recentStudents'=>Student::latest()->take(6)->get()
        ]);
    }
}
