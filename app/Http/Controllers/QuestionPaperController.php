<?php
namespace App\Http\Controllers;
use App\Models\QuestionPaper; use App\Models\Student; use Illuminate\Http\Request; use Illuminate\Validation\Rule;
class QuestionPaperController extends Controller {
 public function index(Request $r){$q=QuestionPaper::with('creator');if(session('portal_user.role')!=='admin')$q->where('created_by',session('portal_user.id'));$q->latest();foreach(['technology','semester','assessment_type','subject_name'] as $f)if($r->filled($f))$q->where($f,'like',$f==='subject_name'?('%'.$r->$f.'%'):$r->$f);$papers=$q->paginate(20)->withQueryString();return view('questions.index',compact('papers'));}
 public function create(){return view('questions.create',['technologies'=>Student::technologyOptions(),'semesters'=>range(1,8),'question'=>null]);}
 public function edit(QuestionPaper $question){$this->authorizePaper($question);return view('questions.create',['technologies'=>Student::technologyOptions(),'semesters'=>range(1,8),'question'=>$question]);}
 public function store(Request $r){
  $d=$this->validatedPaper($r);
  $d['total_marks']=$this->calculatedTotalMarks($d['sections']);
  $d['created_by']=session('portal_user.id');$d['syllabus']='BTEB প্রবিধান ২০২২';$d['questions']=['sections'=>$d['sections']];unset($d['sections']);
  QuestionPaper::create($d);return redirect()->route('questions.index')->with('success','BTEB ফরম্যাটে প্রশ্নপত্র তৈরি হয়েছে।');
 }
 private function authorizePaper(QuestionPaper $question): void {
  $user=session('portal_user',[]);
  abort_unless(($user['role']??null)==='admin' || (int)($question->created_by??0)===(int)($user['id']??0),403,'আপনি এই প্রশ্নপত্র দেখার বা পরিবর্তনের অনুমতি পাননি।');
 }
 public function validatedPaper(Request $r){
  $d=$r->validate([
   'technology'=>['required',Rule::in(Student::technologyOptions())],'semester'=>'required|string|max:20','subject_code'=>'nullable|string|max:40','subject_name'=>'required|string|max:150',
   'assessment_type'=>'required|in:Class Test,Quiz Test,Mid-term Exam,Model Test,Assignment,Final Exam','title'=>'required|string|max:150',
   'board_name'=>'required|string|max:180','program_name'=>'nullable|string|max:180','exam_session'=>'nullable|string|max:100','exam_year'=>'nullable|string|max:20','exam_term'=>'nullable|string|max:100','department_name'=>'nullable|string|max:120',
   'duration_minutes'=>'required|integer|min:5|max:600','total_marks'=>'nullable|numeric|min:0|max:1000','instructions'=>'nullable|string|max:3000',
   'sections'=>'required|array|min:1','sections.*.title'=>'required|string|max:120','sections.*.instruction'=>'nullable|string|max:500','sections.*.answer_count'=>'required|integer|min:1','sections.*.per_question_marks'=>'required|numeric|min:0.25|max:1000','sections.*.questions'=>'required|array|min:1',
   'sections.*.questions.*.text'=>'required|string|max:3000','sections.*.questions.*.topic'=>'nullable|string|max:150','sections.*.questions.*.type'=>'required|in:Creative,Short,MCQ,Practical',
  ]);
  foreach($d['sections'] as $i=>&$section){
   $required=(int)$section['answer_count'];
   if($required>count($section['questions']))throw \Illuminate\Validation\ValidationException::withMessages(["sections.$i.answer_count"=>'উত্তর দিতে হবে এমন প্রশ্নের সংখ্যা মোট প্রশ্নের চেয়ে বেশি হতে পারবে না।']);
   // All questions in a section carry the same mark value. Store it on each question for compatibility.
   foreach($section['questions'] as &$question){$question['marks']=(float)$section['per_question_marks'];}
   unset($question);
  }
  unset($section);
  return $d;
 }
 private function calculatedTotalMarks(array $sections): float {
  return collect($sections)->sum(function($section){return (float)$section['per_question_marks']*(int)$section['answer_count'];});
 }
 public function update(Request $r, QuestionPaper $question){
  $this->authorizePaper($question);
  $d=$this->validatedPaper($r);
  $d['total_marks']=$this->calculatedTotalMarks($d['sections']);
  $d['questions']=['sections'=>$d['sections']];unset($d['sections']);
  $question->update($d);
  return redirect()->route('questions.index')->with('success','প্রশ্নপত্র সফলভাবে আপডেট হয়েছে।');
 }
 public function show(QuestionPaper $question){$this->authorizePaper($question);return view('questions.show',compact('question'));}
 public function destroy(QuestionPaper $question){$this->authorizePaper($question);$question->delete();return back()->with('success','প্রশ্নপত্র মুছে ফেলা হয়েছে।');}
}
