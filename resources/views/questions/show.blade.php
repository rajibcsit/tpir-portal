@extends('layouts.app')
@section('title',$question->title) @section('page-heading','Question Paper Preview')
@section('content')
<div class="no-print mb-4 flex flex-wrap justify-between gap-2"><a href="{{ route('questions.index') }}" class="btn border bg-white">← Back</a><button onclick="window.print()" class="btn btn-primary">🖨 Print Question Paper</button></div>
@php($payload=$question->questions ?? [])
<div class="paper-sheet mx-auto max-w-4xl bg-white p-7 text-black shadow-sm print-area">
<header class="text-center leading-relaxed"><h1 class="text-xl font-bold">{{ $question->board_name ?: 'বাংলাদেশ কারিগরি শিক্ষা বোর্ড, ঢাকা' }}</h1><div class="text-base font-semibold">{{ $question->program_name ?: 'ডিপ্লোমা-ইন-ইঞ্জিনিয়ারিং' }}</div><div class="font-semibold">{{ $question->exam_session }} {{ $question->exam_term }} {{ $question->exam_year ? '— '.$question->exam_year : '' }}</div><div class="font-bold">{{ $question->department_name ?: $question->technology }}</div><div class="font-semibold">বিষয় : {{ $question->subject_name }}</div><div class="font-semibold">বিষয় কোড : {{ $question->subject_code }}</div></header>
<div class="mt-4 flex justify-between text-sm font-semibold"><span>সময় : {{ $question->duration_minutes }} মিনিট</span><span>পূর্ণমান : {{ rtrim(rtrim(number_format($question->total_marks,2,'.',''),'0'),'.') }}</span></div>
<div class="mt-4 border-y border-black py-2 text-center text-sm font-semibold">{{ $question->title }} — {{ $question->assessment_type }}</div>
@if($question->instructions)<p class="mt-3 text-sm font-semibold">{{ $question->instructions }}</p>@endif
@if(isset($payload['sections']))
@php($serial=0)
@foreach($payload['sections'] as $section)
<section class="mt-5"><h2 class="text-center font-bold underline">{{ $section['title'] }}</h2>@if(!empty($section['instruction']))<p class="mt-1 text-sm font-semibold">{{ $section['instruction'] }}</p>@endif
<div class="mt-3 space-y-4">@foreach($section['questions'] as $q)@php($serial++)<div class="flex gap-3 text-[15px] leading-relaxed"><div class="shrink-0 font-semibold">{{ $serial }}.</div><div class="min-w-0 flex-1"><div class="whitespace-pre-line">{{ $q['text'] }}</div>@if(!empty($q['topic']))<div class="mt-1 text-xs italic">অধ্যায়: {{ $q['topic'] }}</div>@endif</div><div class="shrink-0 whitespace-nowrap font-semibold">[{{ rtrim(rtrim(number_format((float)$q['marks'],2,'.',''),'0'),'.') }}]</div></div>@endforeach</div></section>
@endforeach
@else
{{-- Backward compatibility with question papers saved in the earlier v6 format --}}
<section class="mt-5 space-y-4">@foreach($payload as $i=>$q)@if(is_array($q) && isset($q['text']))<div class="flex gap-3 text-[15px] leading-relaxed"><div class="font-semibold">{{ $i+1 }}.</div><div class="flex-1 whitespace-pre-line">{{ $q['text'] }}</div><div class="font-semibold">[{{ $q['marks'] ?? '' }}]</div></div>@endif @endforeach</section>
@endif
<div class="mt-10 text-center text-sm font-semibold">— সমাপ্ত —</div><div class="mt-10 grid grid-cols-2 gap-10 text-center text-xs"><div>প্রস্তুতকারকের স্বাক্ষর</div><div>বিভাগীয় প্রধানের স্বাক্ষর</div></div>
</div>
@endsection
@push('styles')<style>@page{size:A4;margin:16mm} @media print{.paper-sheet header{display:block!important}body{background:#fff!important}.no-print,aside,header.sticky{display:none!important}main{padding:0!important;margin:0!important}.paper-sheet{max-width:none!important;box-shadow:none!important;padding:0!important}.print-area{break-inside:auto} .print-area section,.print-area .flex{break-inside:avoid} }</style>@endpush
