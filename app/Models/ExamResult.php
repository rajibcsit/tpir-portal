<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ExamResult extends Model {
    protected $fillable = ['student_id','exam_name','exam_year','semester','subject_code','subject_name','full_marks','pass_marks','credit','marks','letter_grade','grade_point'];
    public function student() { return $this->belongsTo(Student::class); }
}
