<?php
namespace App\Http\Controllers;
use App\Models\Student; use App\Models\Attendance; use App\Models\Payment; use Illuminate\Support\Facades\DB;
class DashboardController extends Controller {
 public function index() {
  $today=now()->toDateString();
  $attendanceTrend=collect(range(6,0))->map(function($days){$date=now()->subDays($days);return ['label'=>$date->format('d M'),'present'=>Attendance::whereDate('attendance_date',$date->toDateString())->whereIn('status',['Present','Late'])->count(),'absent'=>Attendance::whereDate('attendance_date',$date->toDateString())->where('status','Absent')->count()];});
  $collectionTrend=collect(range(5,0))->map(function($months){$date=now()->startOfMonth()->subMonths($months);return ['label'=>$date->format('M Y'),'amount'=>(float)Payment::whereYear('paid_date',$date->year)->whereMonth('paid_date',$date->month)->sum('amount_paid')];});
  return view('dashboard',['studentCount'=>Student::count(),'activeCount'=>Student::where('status','Active')->count(),'todayPresent'=>Attendance::whereDate('attendance_date',$today)->whereIn('status',['Present','Late'])->count(),'todayAbsent'=>Attendance::whereDate('attendance_date',$today)->where('status','Absent')->count(),'totalDue'=>max(0,(float)Payment::sum('amount_due')-(float)Payment::sum('amount_paid')),'totalCollected'=>(float)Payment::sum('amount_paid'),'recentStudents'=>Student::latest()->take(8)->get(),'attendanceTrend'=>$attendanceTrend,'collectionTrend'=>$collectionTrend]);
 }
}
