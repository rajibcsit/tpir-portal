<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,DashboardController,StudentController,AttendanceController,PaymentController,StudentCardController,MonthlyAttendanceController,ExamResultController,StudentPortalController,UserController,StudentPhotoController,QuestionPaperController};

Route::get('/login',[AuthController::class,'showLogin'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login.post');

Route::middleware('portal.auth')->group(function(){
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('/students/{student}/photo',[StudentPhotoController::class,'show'])->name('students.photo');

    Route::middleware('portal.role:student')->group(function(){
        Route::get('/student-portal',[StudentPortalController::class,'index'])->name('student.portal');
        Route::get('/my-id-card',[StudentCardController::class,'mine'])->name('student.id-card');
    });

    Route::middleware('portal.role:admin')->group(function(){
        Route::get('/',[DashboardController::class,'index'])->name('dashboard');
        Route::get('/students/create',[StudentController::class,'create'])->name('students.create');
        Route::post('/students',[StudentController::class,'store'])->name('students.store');
        Route::get('/students/{student}/edit',[StudentController::class,'edit'])->name('students.edit');
        Route::put('/students/{student}',[StudentController::class,'update'])->name('students.update');
        Route::delete('/students/{student}',[StudentController::class,'destroy'])->name('students.destroy');
        Route::resource('users',UserController::class)->except(['show']);
    });

    Route::middleware('portal.role:admin,teacher,accountant')->group(function(){
        Route::get('/students',[StudentController::class,'index'])->name('students.index');
        Route::get('/students/{student}',[StudentController::class,'show'])->name('students.show');
        Route::get('/students-print',[StudentController::class,'print'])->name('students.print');
        Route::get('/students/{student}/id-card',[StudentCardController::class,'show'])->name('students.id-card');
    });

    Route::middleware('portal.role:admin,teacher')->group(function(){
        Route::get('/attendance',[AttendanceController::class,'index'])->name('attendance.index');
        Route::post('/attendance',[AttendanceController::class,'store'])->name('attendance.store');
        Route::get('/attendance/report',[AttendanceController::class,'report'])->name('attendance.report');
        Route::get('/attendance/monthly',[MonthlyAttendanceController::class,'index'])->name('attendance.monthly');
        Route::get('/results',[ExamResultController::class,'index'])->name('results.index');
        Route::get('/questions',[QuestionPaperController::class,'index'])->name('questions.index');
        Route::get('/questions/create',[QuestionPaperController::class,'create'])->name('questions.create');
        Route::post('/questions',[QuestionPaperController::class,'store'])->name('questions.store');
        Route::get('/questions/{question}/edit',[QuestionPaperController::class,'edit'])->name('questions.edit');
        Route::put('/questions/{question}',[QuestionPaperController::class,'update'])->name('questions.update');
        Route::get('/questions/{question}',[QuestionPaperController::class,'show'])->name('questions.show');
        Route::delete('/questions/{question}',[QuestionPaperController::class,'destroy'])->name('questions.destroy');
        Route::post('/results',[ExamResultController::class,'store'])->name('results.store');
        Route::delete('/results/{result}',[ExamResultController::class,'destroy'])->name('results.destroy');
    });

    Route::middleware('portal.role:admin,accountant')->group(function(){
        Route::get('/payments/{payment}/collect',[PaymentController::class,'collectForm'])->name('payments.collect.form');
        Route::post('/payments/{payment}/collect',[PaymentController::class,'collect'])->name('payments.collect');
        Route::get('/payments/student-search',[PaymentController::class,'studentSearch'])->name('payments.students.search');
        Route::get('/payments-print',[PaymentController::class,'print'])->name('payments.print');
        Route::get('/payments/{payment}/receipt',[PaymentController::class,'receipt'])->name('payments.receipt');
        Route::resource('payments',PaymentController::class)->except(['show']);
    });
});
