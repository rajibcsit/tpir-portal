@extends('layouts.app')
@section('title',$question->title) @section('page-heading','Question Paper Preview')
@section('content')
<div class="no-print mb-4 flex flex-wrap justify-between gap-2"><div class="flex gap-2"><a href="{{ route('questions.index') }}" class="btn border bg-white">← Back</a><a href="{{ route('questions.edit',$question) }}" class="btn border bg-white">Edit</a></div><button onclick="window.print()" class="btn btn-primary">🖨 Print Question Paper</button></div>
@php($payload=$question->questions ?? [])
<div class="paper-sheet mx-auto max-w-4xl bg-white p-5 text-black shadow-sm print-area">
<header class="text-center leading-snug"><h1 class="text-xl font-bold">{{ $question->board_name ?: 'TMSS Polytechnic Institute, Rangpur' }}</h1><div class="text-base font-semibold">{{ $question->program_name ?: 'ডিপ্লোমা-ইন-ইঞ্জিনিয়ারিং' }}</div><div class="font-semibold">{{ $question->exam_session }} {{ $question->exam_term }} {{ $question->exam_year ? '— '.$question->exam_year : '' }}</div><div class="font-bold">{{ $question->department_name ?: $question->technology }}</div><div class="font-semibold">বিষয় : {{ $question->subject_name }}</div><div class="font-semibold">বিষয় কোড : {{ $question->subject_code }}</div></header>
<div class="mt-2 flex justify-between text-sm font-semibold"><span>সময় : {{ $question->duration_minutes }} মিনিট</span><span>পূর্ণমান : {{ rtrim(rtrim(number_format($question->total_marks,2,'.',''),'0'),'.') }}</span></div>
<div class="mt-2 border-y border-black py-1.5 text-center text-sm font-semibold">{{ $question->title }} — {{ $question->assessment_type }}</div>
@if($question->instructions)<p class="mt-2 text-sm font-semibold">{{ $question->instructions }}</p>@endif
@if(isset($payload['sections']))
@php($serial=0)
@foreach($payload['sections'] as $section)
@php($sectionQuestions=collect($section['questions'] ?? []))
@php($requiredAnswers=(int)($section['answer_count'] ?? $sectionQuestions->count()))
@php($perQuestionMarks=(float)($section['per_question_marks'] ?? ($sectionQuestions->first()['marks'] ?? 0)))
@php($sectionMarks=$perQuestionMarks*$requiredAnswers)
@php($cleanSectionTitle=preg_replace('/\s*\(\s*মান\s*[:：].*?\)\s*$/u','',trim($section['title'] ?? '')))
@php($sectionInstruction=trim($section['instruction'] ?? ''))
<section class="mt-3"><h2 class="text-center font-bold underline">{{ $cleanSectionTitle }} (মান: {{ rtrim(rtrim(number_format($perQuestionMarks,2,'.',''),'0'),'.') }}×{{ $requiredAnswers }}={{ rtrim(rtrim(number_format($sectionMarks,2,'.',''),'0'),'.') }})</h2>
<p class="mt-1 text-sm font-semibold">{{ $sectionInstruction !== '' ? $sectionInstruction : ($requiredAnswers < $sectionQuestions->count() ? 'যে কোনো '.$requiredAnswers.'টি প্রশ্নের উত্তর দাও' : 'সকল প্রশ্নের উত্তর দাও') }}</p>
<div class="mt-2 space-y-2">@foreach($section['questions'] as $q)@php($serial++)<div class="flex gap-2 text-[14px] leading-snug"><div class="shrink-0 font-semibold">{{ $serial }}.</div><div class="min-w-0 flex-1"><div class="whitespace-pre-line">{{ $q['text'] }}</div>@if(!empty($q['topic']))<div class="mt-0.5 text-xs italic">অধ্যায়: {{ $q['topic'] }}</div>@endif</div></div>@endforeach</div></section>
@endforeach
@else
{{-- Backward compatibility with question papers saved in the earlier v6 format --}}
<section class="mt-5 space-y-4">@foreach($payload as $i=>$q)@if(is_array($q) && isset($q['text']))<div class="flex gap-3 text-[15px] leading-relaxed"><div class="font-semibold">{{ $i+1 }}.</div><div class="flex-1 whitespace-pre-line">{{ $q['text'] }}</div><div class="font-semibold">[{{ $q['marks'] ?? '' }}]</div></div>@endif @endforeach</section>
@endif
<div class="mt-5 text-center text-sm font-semibold">— সমাপ্ত —</div><div class="mt-5 grid grid-cols-2 gap-8 text-center text-xs"><div>প্রস্তুতকারকের স্বাক্ষর</div><div>বিভাগীয় প্রধানের স্বাক্ষর</div></div>
</div>
@endsection
@push('styles')<style>@page{size:A4;margin:10mm} @media print{.paper-sheet header{display:block!important}body{background:#fff!important}.no-print,aside,header.sticky{display:none!important}main{padding:0!important;margin:0!important}.paper-sheet{max-width:none!important;box-shadow:none!important;padding:0!important;font-size:12px!important;line-height:1.25!important}.paper-sheet header h1{font-size:17px!important}.paper-sheet section{margin-top:10px!important}.paper-sheet section p{margin-top:3px!important}.paper-sheet .question-list{gap:5px!important}.print-area{break-inside:auto} .print-area section,.print-area .flex{break-inside:avoid} }</style>@endpush
