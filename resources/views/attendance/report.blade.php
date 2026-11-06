@extends('layouts.app')
@section('title','হাজিরা রিপোর্ট')
@section('content')
<h1 class="text-2xl font-bold mb-5">হাজিরা রিপোর্ট</h1>
<form class="bg-white rounded-xl p-4 mb-5 flex flex-wrap gap-3 items-end"><div><label class="block text-sm mb-1">শুরুর তারিখ</label><input type="date" name="from" value="{{ $from }}" class="border rounded-lg px-3 py-2"></div><div><label class="block text-sm mb-1">শেষ তারিখ</label><input type="date" name="to" value="{{ $to }}" class="border rounded-lg px-3 py-2"></div><button class="bg-slate-800 text-white px-5 py-2 rounded-lg">রিপোর্ট</button></form>
<div class="bg-white rounded-xl shadow-sm overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50"><tr><th class="p-4 text-left">তারিখ</th><th class="p-4 text-left">আইডি</th><th class="p-4 text-left">শিক্ষার্থী</th><th class="p-4 text-left">বিভাগ</th><th class="p-4 text-left">অবস্থা</th></tr></thead><tbody>@forelse($rows as $r)<tr class="border-t"><td class="p-4">{{ $r->attendance_date->format('d-m-Y') }}</td><td class="p-4">{{ $r->student?->student_id }}</td><td class="p-4">{{ $r->student?->name }}</td><td class="p-4">{{ $r->student?->department }}</td><td class="p-4">{{ $r->status }}</td></tr>@empty<tr><td colspan="5" class="p-8 text-center text-slate-400">এই সময়ের কোনো রেকর্ড নেই</td></tr>@endforelse</tbody></table><div class="p-4">{{ $rows->links() }}</div></div>
@endsection
