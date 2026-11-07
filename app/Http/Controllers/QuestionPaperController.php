<?php
namespace App\Http\Controllers;
use App\Models\QuestionPaper; use App\Models\Student; use Illuminate\Http\Request; use Illuminate\Validation\Rule;
class QuestionPaperController extends Controller {
 public function index(Request $r){$q=QuestionPaper::query()->latest();foreach(['technology','semester','assessment_type','subject_name'] as $f)if($r->filled($f))$q->where($f,'like',$f==='subject_name'?('%'.$r->$f.'%'):$r->$f);$papers=$q->paginate(20)->withQueryString();return view('questions.index',compact('papers'));}
 public function create(){return view('questions.create',['technologies'=>Student::technologyOptions(),'semesters'=>range(1,8)]);}
 public function store(Request $r){
  $d=$r->validate([
   'technology'=>['required',Rule::in(Student::technologyOptions())],'semester'=>'required|string|max:20','subject_code'=>'nullable|string|max:40','subject_name'=>'required|string|max:150',
   'assessment_type'=>'required|in:Class Test,Quiz Test,Mid-term Exam,Model Test,Assignment,Final Exam','title'=>'required|string|max:150',
   'board_name'=>'nullable|string|max:180','program_name'=>'nullable|string|max:180','exam_session'=>'nullable|string|max:100','exam_year'=>'nullable|string|max:20','exam_term'=>'nullable|string|max:100','department_name'=>'nullable|string|max:120',
   'duration_minutes'=>'required|integer|min:5|max:600','total_marks'=>'required|numeric|min:1|max:1000','instructions'=>'nullable|string|max:3000',
   'sections'=>'required|array|min:1','sections.*.title'=>'required|string|max:120','sections.*.instruction'=>'nullable|string|max:500','sections.*.questions'=>'required|array|min:1',
   'sections.*.questions.*.text'=>'required|string|max:3000','sections.*.questions.*.topic'=>'nullable|string|max:150','sections.*.questions.*.marks'=>'required|numeric|min:0.25|max:1000','sections.*.questions.*.type'=>'required|in:Creative,Short,MCQ,Practical',
  ]);
  $sum=collect($d['sections'])->sum(fn($s)=>collect($s['questions'])->sum(fn($q)=>(float)$q['marks']));
  if(abs($sum-(float)$d['total_marks'])>0.01)return back()->withErrors(['total_marks'=>'সব বিভাগের প্রশ্নের নম্বরের যোগফল পূর্ণমানের সমান হতে হবে।'])->withInput();
  $d['created_by']=session('portal_user.id');$d['syllabus']='BTEB প্রবিধান ২০২২';$d['questions']=['sections'=>$d['sections']];unset($d['sections']);
  QuestionPaper::create($d);return redirect()->route('questions.index')->with('success','BTEB ফরম্যাটে প্রশ্নপত্র তৈরি হয়েছে।');
 }
 public function show(QuestionPaper $question){return view('questions.show',compact('question'));}
 public function destroy(QuestionPaper $question){$question->delete();return back()->with('success','প্রশ্নপত্র মুছে ফেলা হয়েছে।');}
}
