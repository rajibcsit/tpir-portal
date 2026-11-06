@extends('layouts.app')
@section('title','ড্যাশবোর্ড')
@section('content')
<div class="flex flex-wrap justify-between items-center gap-3 mb-7"><div><h1 class="text-2xl font-bold">ড্যাশবোর্ড</h1><p class="text-slate-500">প্রতিষ্ঠানের শিক্ষার্থী ও আর্থিক সারসংক্ষেপ</p></div><a href="{{ route('students.create') }}" class="bg-blue-700 text-white px-4 py-2 rounded-lg">+ নতুন শিক্ষার্থী</a></div>
<div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-7">
@foreach([['মোট শিক্ষার্থী',$studentCount,'👨‍🎓','bg-blue-50'],['সক্রিয় শিক্ষার্থী',$activeCount,'✓','bg-emerald-50'],['আজ উপস্থিত',$todayPresent,'◉','bg-violet-50'],['মোট বকেয়া','৳ '.number_format($totalDue,2),'৳','bg-amber-50']] as $item)
<div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100"><div class="flex justify-between"><span class="text-slate-500">{{ $item[0] }}</span><span class="rounded-lg p-2 {{ $item[3] }}">{{ $item[2] }}</span></div><div class="text-2xl font-bold mt-3">{{ $item[1] }}</div></div>
@endforeach
</div>
<div class="grid lg:grid-cols-3 gap-5">
<div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5"><div class="flex justify-between mb-4"><h2 class="font-bold text-lg">সর্বশেষ যুক্ত শিক্ষার্থী</h2><a class="text-blue-700" href="{{ route('students.index') }}">সব দেখুন →</a></div>
<div class="overflow-x-auto"><table class="w-full text-sm"><thead class="text-slate-500 border-b"><tr><th class="text-left py-3">আইডি</th><th class="text-left">নাম</th><th class="text-left">টেকনোলজি</th><th class="text-left">অবস্থা</th></tr></thead><tbody>@forelse($recentStudents as $s)<tr class="border-b last:border-0"><td class="py-3">{{ $s->student_id }}</td><td><a class="text-blue-700" href="{{ route('students.show',$s) }}">{{ $s->name }}</a></td><td>{{ $s->technology ?: $s->department }}</td><td>{{ $s->status }}</td></tr>@empty<tr><td colspan="4" class="py-8 text-center text-slate-400">এখনো কোনো শিক্ষার্থী যুক্ত হয়নি</td></tr>@endforelse</tbody></table></div></div>
<div class="bg-white rounded-xl shadow-sm p-5"><h2 class="font-bold text-lg mb-4">দ্রুত কাজ</h2><div class="space-y-3"><a class="block p-3 rounded-lg bg-slate-50 hover:bg-blue-50" href="{{ route('attendance.index') }}">✓ আজকের হাজিরা নিন</a><a class="block p-3 rounded-lg bg-slate-50 hover:bg-blue-50" href="{{ route('payments.create') }}">৳ নতুন ফি এন্ট্রি</a><a class="block p-3 rounded-lg bg-slate-50 hover:bg-blue-50" href="{{ route('attendance.report') }}">▤ হাজিরা রিপোর্ট</a><a class="block p-3 rounded-lg bg-slate-50 hover:bg-blue-50" href="{{ route('attendance.monthly') }}">▦ মাসিক হাজিরা শতাংশ</a><a class="block p-3 rounded-lg bg-slate-50 hover:bg-blue-50" href="{{ route('results.index') }}">🏅 Exam Result ও GPA</a><p class="text-sm text-slate-500 pt-3">আজ অনুপস্থিত: <b>{{ $todayAbsent }}</b> জন</p></div></div>
</div>
@endsection
