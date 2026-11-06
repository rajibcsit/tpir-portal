<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,DashboardController,StudentController,AttendanceController,PaymentController,StudentCardController,MonthlyAttendanceController,ExamResultController,StudentPortalController};
Route::get('/login',[AuthController::class,'showLogin'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login.post');
Route::middleware('portal.auth')->group(function(){
 Route::post('/logout',[AuthController::class,'logout'])->name('logout');
 Route::get('/student-portal',[StudentPortalController::class,'index'])->name('student.portal');
 Route::get('/my-id-card',[StudentCardController::class,'mine'])->name('student.id-card');
 Route::get('/',[DashboardController::class,'index'])->name('dashboard');
 Route::resource('students',StudentController::class);
 Route::get('/attendance',[AttendanceController::class,'index'])->name('attendance.index'); Route::post('/attendance',[AttendanceController::class,'store'])->name('attendance.store'); Route::get('/attendance/report',[AttendanceController::class,'report'])->name('attendance.report'); Route::get('/attendance/monthly',[MonthlyAttendanceController::class,'index'])->name('attendance.monthly');
 Route::get('/students/{student}/id-card',[StudentCardController::class,'show'])->name('students.id-card');
 Route::get('/results',[ExamResultController::class,'index'])->name('results.index'); Route::post('/results',[ExamResultController::class,'store'])->name('results.store'); Route::delete('/results/{result}',[ExamResultController::class,'destroy'])->name('results.destroy');
 Route::get('/payments/student-search',[PaymentController::class,'studentSearch'])->name('payments.students.search');
 Route::resource('payments',PaymentController::class)->except(['show']);
});
