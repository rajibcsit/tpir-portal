@extends('layouts.app')
@section('title','ফি ও পেমেন্ট')
@section('page-heading','ফি ও পেমেন্ট ব্যবস্থাপনা')
@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div><div class="text-sm font-semibold text-blue-700">ACCOUNTS / COLLECTION</div><h1 class="mt-1 text-2xl font-bold text-slate-900">ফি ও পেমেন্ট ব্যবস্থাপনা</h1><p class="mt-1 text-sm text-slate-500">হাজারো শিক্ষার্থীর মধ্যেও Roll, Technology, Semester ও Session দিয়ে দ্রুত পেমেন্ট খুঁজুন।</p></div>
    <div class="flex gap-2 no-print"><a href="{{ route('payments.print', request()->query()) }}" target="_blank" class="btn border bg-white">🖨 Print Report</a><a href="{{ route('payments.create') }}" class="btn btn-primary">＋ নতুন পেমেন্ট এন্ট্রি</a></div>
</div>
<div class="mb-5 grid gap-3 sm:grid-cols-3">
    <div class="panel p-4"><div class="text-sm text-slate-500">মোট পেমেন্ট রেকর্ড</div><div class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($stats['total']) }}</div></div>
    <div class="panel p-4"><div class="text-sm text-slate-500">মোট আদায়</div><div class="mt-2 text-2xl font-bold text-emerald-700">৳{{ number_format($stats['collected'], 2) }}</div></div>
    <div class="panel p-4"><div class="text-sm text-slate-500">মোট বকেয়া</div><div class="mt-2 text-2xl font-bold text-rose-700">৳{{ number_format($stats['due'], 2) }}</div></div>
</div>
<section class="panel mb-5 overflow-hidden">
    <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-4"><div class="font-bold">শিক্ষার্থী ও পেমেন্ট সার্চ</div><p class="mt-1 text-xs text-slate-500">একাধিক ফিল্টার একসঙ্গে ব্যবহার করতে পারবেন।</p></div>
    <form method="GET" class="grid gap-3 p-5 sm:grid-cols-2 xl:grid-cols-4">
        <div class="sm:col-span-2"><label class="mb-1 block text-xs font-semibold text-slate-600">Roll / শিক্ষার্থীর নাম / মোবাইল</label><input name="search" value="{{ request('search') }}" class="field" placeholder="Roll বা নাম লিখুন"></div>
        
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Technology</label><select name="technology" class="field"><option value="">সব Technology</option>@foreach($technologies as $v)<option value="{{ $v }}" @selected(request('technology')===$v)>{{ $v }}</option>@endforeach</select></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Semester</label><select name="semester" class="field"><option value="">সব Semester</option>@foreach($semesters as $v)<option value="{{ $v }}" @selected(request('semester')===$v)>{{ $v }}</option>@endforeach</select></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">Session</label><select name="session" class="field"><option value="">সব Session</option>@foreach($sessions as $v)<option value="{{ $v }}" @selected(request('session')===$v)>{{ $v }}</option>@endforeach</select></div>
        <div><label class="mb-1 block text-xs font-semibold text-slate-600">পেমেন্ট অবস্থা</label><select name="filter" class="field"><option value="">সব পেমেন্ট</option><option value="due" @selected(request('filter')==='due')>শুধু বকেয়া</option></select></div>
        <div class="flex items-end gap-2 xl:col-span-4"><button class="btn btn-primary">⌕ সার্চ করুন</button><a href="{{ route('payments.index') }}" class="btn border border-slate-200 bg-white">সব রিসেট</a></div>
    </form>
</section>
<div class="panel overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4"><div class="font-bold">পেমেন্ট রেকর্ড</div><div class="text-xs text-slate-500">{{ $payments->total() }}টি রেকর্ড পাওয়া গেছে</div></div>
    <div class="overflow-x-auto"><table class="w-full min-w-[850px] text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3 text-left">শিক্ষার্থী</th><th class="px-4 py-3 text-left">Technology / Semester</th><th class="px-4 py-3 text-left">ফি বিবরণ</th><th class="px-4 py-3 text-right">নির্ধারিত</th><th class="px-4 py-3 text-right">পরিশোধ</th><th class="px-4 py-3 text-right">বকেয়া</th><th class="px-5 py-3 text-center">অ্যাকশন</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
        @forelse($payments as $p)
            <tr class="hover:bg-slate-50/70"><td class="px-5 py-4"><div class="font-bold text-slate-900">{{ $p->student?->name ?? 'Deleted student' }}</div><div class="mt-1 text-xs text-slate-500">Roll: {{ $p->student?->student_id ?? '—' }} · {{ $p->student?->phone ?? 'No phone' }}</div></td><td class="px-4 py-4"><div class="font-medium">{{ $p->student?->technology ?? '—' }}</div><div class="mt-1 text-xs text-slate-500">{{ $p->student?->semester ?? '—' }} Semester · {{ $p->student?->session ?? '—' }}</div></td><td class="px-4 py-4"><div class="font-medium">{{ $p->title }}</div><div class="mt-1 text-xs text-slate-500">{{ $p->paid_date?->format('d M Y') ?? 'তারিখ নেই' }} · {{ $p->method ?? '—' }}</div></td><td class="px-4 py-4 text-right font-medium">৳{{ number_format($p->amount_due,2) }}</td><td class="px-4 py-4 text-right font-semibold text-emerald-700">৳{{ number_format($p->amount_paid,2) }}</td><td class="px-4 py-4 text-right font-semibold {{ $p->due > 0 ? 'text-rose-700' : 'text-emerald-700' }}">৳{{ number_format($p->due,2) }}</td><td class="px-5 py-4"><div class="flex justify-center gap-3"><a class="font-semibold text-blue-700 hover:underline" href="{{ route('payments.receipt',$p) }}" target="_blank">Receipt</a><a class="font-semibold text-blue-700 hover:underline" href="{{ route('payments.edit',$p) }}">এডিট</a><form method="POST" action="{{ route('payments.destroy',$p) }}" onsubmit="return confirm('এই পেমেন্ট রেকর্ড মুছে ফেলবেন?')">@csrf @method('DELETE')<button class="font-semibold text-rose-600 hover:underline">ডিলিট</button></form></div></td></tr>
        @empty
            <tr><td colspan="7" class="px-5 py-14 text-center"><div class="text-3xl">⌕</div><div class="mt-2 font-semibold text-slate-700">কোনো পেমেন্ট রেকর্ড পাওয়া যায়নি</div><div class="mt-1 text-sm text-slate-500">সার্চ বা ফিল্টার পরিবর্তন করে আবার চেষ্টা করুন।</div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    <div class="border-t border-slate-100 px-5 py-4">{{ $payments->links() }}</div>
</div>
@endsection
