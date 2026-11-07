<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class QuestionPaper extends Model {
 protected $fillable=['created_by','technology','semester','subject_code','subject_name','assessment_type','title','board_name','program_name','exam_session','exam_year','exam_term','department_name','syllabus','duration_minutes','total_marks','instructions','questions'];
 protected $casts=['questions'=>'array'];
 public function creator(){return $this->belongsTo(User::class,'created_by');}
}
