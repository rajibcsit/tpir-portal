@extends('layouts.app')
@section('title', $payment->exists ? 'পেমেন্ট আপডেট' : 'নতুন পেমেন্ট')
@section('page-heading', 'ফি ও পেমেন্ট ব্যবস্থাপনা')
@section('content')
@php($isEdit = $payment->exists)
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div><div class="text-sm font-semibold text-blue-700">ACCOUNTS / PAYMENTS</div><h1 class="mt-1 text-2xl font-bold text-slate-900">{{ $isEdit ? 'পেমেন্ট তথ্য আপডেট' : 'নতুন পেমেন্ট এন্ট্রি' }}</h1><p class="mt-1 text-sm text-slate-500">রোল, নাম, Technology, Semester অথবা Session দিয়ে শিক্ষার্থী খুঁজুন।</p></div>
    <a href="{{ route('payments.index') }}" class="btn border border-slate-200 bg-white text-slate-700">← পেমেন্ট তালিকা</a>
</div>
<form method="POST" action="{{ $isEdit ? route('payments.update', $payment) : route('payments.store') }}" class="space-y-5" id="paymentForm">
    @csrf @if($isEdit) @method('PUT') @endif
    <section class="panel overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-4"><div class="font-bold text-slate-900">১. শিক্ষার্থী খুঁজুন ও নির্বাচন করুন</div><p class="mt-1 text-xs text-slate-500">একসঙ্গে সব শিক্ষার্থী লোড হয় না। ফিল্টার দিয়ে সর্বোচ্চ ৩০টি মিল দেখানো হবে।</p></div>
        <div class="p-5 space-y-4">
            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <div><label class="mb-1.5 block text-sm font-semibold">Department</label><select id="department" class="field"><option value="">সব Department</option>@foreach($departments as $v)<option value="{{ $v }}">{{ $v }}</option>@endforeach</select></div>
                <div><label class="mb-1.5 block text-sm font-semibold">Technology</label><select id="technology" class="field"><option value="">সব Technology</option>@foreach($technologies as $v)<option value="{{ $v }}">{{ $v }}</option>@endforeach</select></div>
                <div><label class="mb-1.5 block text-sm font-semibold">Semester</label><select id="semester" class="field"><option value="">সব Semester</option>@foreach($semesters as $v)<option value="{{ $v }}">{{ $v }}</option>@endforeach</select></div>
                <div><label class="mb-1.5 block text-sm font-semibold">Session</label><select id="session" class="field"><option value="">সব Session</option>@foreach($sessions as $v)<option value="{{ $v }}">{{ $v }}</option>@endforeach</select></div>
            </div>
            <div class="grid gap-3 md:grid-cols-[1fr_auto]">
                <div><label class="mb-1.5 block text-sm font-semibold">Student Roll / নাম / মোবাইল নম্বর</label><input id="studentSearch" class="field" placeholder="যেমন: 123456, Rahim অথবা 017xxxxxxxx" autocomplete="off"><p class="mt-1 text-xs text-slate-400">Roll number দিয়ে খুঁজলে সবচেয়ে দ্রুত ফলাফল পাবেন।</p></div>
                <div class="flex items-end gap-2"><button type="button" id="searchStudents" class="btn btn-primary w-full md:w-auto">⌕ শিক্ষার্থী খুঁজুন</button><button type="button" id="resetSearch" class="btn border border-slate-200 bg-white">Reset</button></div>
            </div>
            <div id="searchMessage" class="hidden rounded-xl bg-blue-50 px-4 py-3 text-sm text-blue-800"></div>
            <div id="studentResults" class="hidden overflow-hidden rounded-xl border border-slate-200">
                <div class="flex items-center justify-between bg-slate-50 px-4 py-3"><span class="text-sm font-bold">সার্চ রেজাল্ট</span><span id="resultCount" class="text-xs text-slate-500"></span></div>
                <div id="studentResultList" class="divide-y divide-slate-100"></div>
            </div>
            <input type="hidden" name="student_id" id="selectedStudentId" value="{{ old('student_id', $selectedStudent?->id ?? '') }}">
            <div id="selectedStudentCard" class="{{ $selectedStudent ? '' : 'hidden' }} rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div><div class="text-xs font-bold uppercase tracking-wide text-emerald-700">নির্বাচিত শিক্ষার্থী</div><div id="selectedStudentName" class="mt-1 font-bold text-slate-900">{{ $selectedStudent?->name }}</div><div id="selectedStudentMeta" class="mt-1 text-sm text-slate-600">{{ $selectedStudent ? 'Roll: '.$selectedStudent->student_id.' · '.$selectedStudent->technology.' · '.$selectedStudent->semester.' Semester · '.$selectedStudent->session : '' }}</div></div>
                    <button type="button" id="changeStudent" class="btn border border-emerald-200 bg-white text-emerald-800">পরিবর্তন করুন</button>
                </div>
            </div>
        </div>
    </section>

    <section class="panel overflow-hidden">
        <div class="border-b border-slate-100 bg-slate-50/80 px-5 py-4"><div class="font-bold text-slate-900">২. ফি ও পেমেন্টের তথ্য</div><p class="mt-1 text-xs text-slate-500">শিক্ষার্থী নির্বাচন করার পর নিচের তথ্য পূরণ করুন।</p></div>
        <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
            <div><label class="mb-1.5 block text-sm font-semibold">ফি / কিস্তির নাম *</label><input name="title" value="{{ old('title', $payment->title) }}" required class="field" placeholder="যেমন: Semester Fee / Exam Fee"></div>
            <div><label class="mb-1.5 block text-sm font-semibold">মোট নির্ধারিত টাকা (৳) *</label><input type="number" step="0.01" min="0" name="amount_due" value="{{ old('amount_due', $payment->amount_due) }}" required class="field" placeholder="5000"></div>
            <div><label class="mb-1.5 block text-sm font-semibold">এখন জমা (৳) *</label><input type="number" step="0.01" min="0" name="amount_paid" value="{{ old('amount_paid', $payment->amount_paid ?? 0) }}" required class="field" placeholder="2000"></div>
            <div><label class="mb-1.5 block text-sm font-semibold">জমার মাধ্যম</label><select name="method" class="field"><option value="">নির্বাচন করুন</option>@foreach(['Cash','bKash','Nagad','Rocket','Bank'] as $m)<option value="{{ $m }}" @selected(old('method', $payment->method) === $m)>{{ $m }}</option>@endforeach</select></div>
            <div><label class="mb-1.5 block text-sm font-semibold">জমার তারিখ</label><input type="date" name="paid_date" value="{{ old('paid_date', $payment->paid_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="field"></div>
            <div><label class="mb-1.5 block text-sm font-semibold">ফি জমার শেষ তারিখ</label><input type="date" name="due_date" value="{{ old('due_date', $payment->due_date?->format('Y-m-d')) }}" class="field"></div>
            <div class="md:col-span-2 xl:col-span-3"><label class="mb-1.5 block text-sm font-semibold">নোট / Transaction ID</label><textarea name="note" rows="2" class="field" placeholder="লেনদেনের নম্বর বা প্রয়োজনীয় তথ্য লিখুন">{{ old('note', $payment->note) }}</textarea></div>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4"><p class="text-xs text-slate-500">* চিহ্নিত তথ্যগুলো আবশ্যক। জমার পর তালিকা থেকে রেকর্ড যাচাই করতে পারবেন।</p><div class="flex gap-2"><a href="{{ route('payments.index') }}" class="btn border border-slate-200 bg-white">বাতিল</a><button type="submit" id="savePayment" class="btn btn-primary" {{ $selectedStudent ? '' : 'disabled' }}>{{ $isEdit ? 'পরিবর্তন সংরক্ষণ' : 'পেমেন্ট সংরক্ষণ করুন' }}</button></div></div>
    </section>
</form>
@endsection

@push('scripts')
<script>
(() => {
    const endpoint = @json(route('payments.students.search'));
    const fields = ['department', 'technology', 'semester', 'session'];
    const searchInput = document.getElementById('studentSearch');
    const resultWrap = document.getElementById('studentResults');
    const resultList = document.getElementById('studentResultList');
    const message = document.getElementById('searchMessage');
    const selectedId = document.getElementById('selectedStudentId');
    const selectedCard = document.getElementById('selectedStudentCard');
    const saveButton = document.getElementById('savePayment');
    const initialStudent = {{ \Illuminate\Support\Js::from($selectedStudent ? [
        'id' => $selectedStudent->id,
        'student_id' => $selectedStudent->student_id,
        'name' => $selectedStudent->name,
        'department' => $selectedStudent->department,
        'technology' => $selectedStudent->technology,
        'semester' => $selectedStudent->semester,
        'session' => $selectedStudent->session,
        'phone' => $selectedStudent->phone,
    ] : null) }};

    function showSelected(student) {
        selectedId.value = student.id;
        document.getElementById('selectedStudentName').textContent = `${student.name} (${student.student_id})`;
        document.getElementById('selectedStudentMeta').textContent = [student.department, student.technology, `${student.semester || '—'} Semester`, `Session ${student.session || '—'}`, student.phone || ''].filter(Boolean).join(' · ');
        selectedCard.classList.remove('hidden');
        saveButton.disabled = false;
        resultWrap.classList.add('hidden');
        message.classList.add('hidden');
    }
    if (initialStudent) showSelected(initialStudent);

    async function searchStudents() {
        const params = new URLSearchParams();
        const term = searchInput.value.trim();
        if (term) params.set('search', term);
        fields.forEach(field => { const value = document.getElementById(field).value; if (value) params.set(field, value); });
        if (!term && !fields.some(field => document.getElementById(field).value)) {
            message.textContent = 'দয়া করে Roll/নাম/মোবাইল লিখুন অথবা Department, Technology, Semester বা Session নির্বাচন করুন।';
            message.classList.remove('hidden'); resultWrap.classList.add('hidden'); return;
        }
        message.textContent = 'শিক্ষার্থী খোঁজা হচ্ছে…'; message.classList.remove('hidden');
        resultWrap.classList.add('hidden');
        try {
            const response = await fetch(`${endpoint}?${params.toString()}`, {headers: {'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});
            if (!response.ok) throw new Error('সার্চ সম্পন্ন করা যায়নি। আবার চেষ্টা করুন।');
            const payload = await response.json();
            resultList.innerHTML = '';
            document.getElementById('resultCount').textContent = `${payload.count} জন দেখানো হচ্ছে · মোট ${payload.total} জন মিলেছে${payload.total > payload.limit ? ' — সঠিক শিক্ষার্থী পেতে Roll/নাম দিয়ে আরও সার্চ করুন' : ''}`;
            if (!payload.data.length) {
                message.textContent = 'কোনো Active শিক্ষার্থী পাওয়া যায়নি। Roll বা ফিল্টার পরিবর্তন করে আবার চেষ্টা করুন।';
                return;
            }
            payload.data.forEach(student => {
                const row = document.createElement('div');
                row.className = 'flex flex-col gap-2 p-4 sm:flex-row sm:items-center sm:justify-between hover:bg-blue-50/60';
                const info = document.createElement('div');
                const title = document.createElement('div'); title.className = 'font-bold text-slate-900'; title.textContent = `${student.student_id} — ${student.name}`;
                const meta = document.createElement('div'); meta.className = 'mt-1 text-xs text-slate-500'; meta.textContent = [student.department, student.technology, `${student.semester || '—'} Semester`, `Session ${student.session || '—'}`, student.phone || ''].filter(Boolean).join(' · ');
                info.append(title, meta);
                const button = document.createElement('button'); button.type = 'button'; button.className = 'btn btn-primary'; button.textContent = 'এই শিক্ষার্থী নির্বাচন'; button.addEventListener('click', () => showSelected(student));
                row.append(info, button); resultList.append(row);
            });
            message.classList.add('hidden'); resultWrap.classList.remove('hidden');
        } catch (error) {
            message.textContent = error.message || 'সার্চে সমস্যা হয়েছে।'; message.classList.remove('hidden');
        }
    }
    document.getElementById('searchStudents').addEventListener('click', searchStudents);
    searchInput.addEventListener('keydown', event => { if (event.key === 'Enter') { event.preventDefault(); searchStudents(); } });
    fields.forEach(field => document.getElementById(field).addEventListener('change', searchStudents));
    document.getElementById('resetSearch').addEventListener('click', () => {
        searchInput.value = ''; fields.forEach(field => document.getElementById(field).value = '');
        resultWrap.classList.add('hidden'); message.classList.add('hidden');
    });
    document.getElementById('changeStudent').addEventListener('click', () => {
        selectedId.value = ''; selectedCard.classList.add('hidden'); saveButton.disabled = true; searchInput.focus();
    });
    document.getElementById('paymentForm').addEventListener('submit', event => {
        if (!selectedId.value) { event.preventDefault(); message.textContent = 'পেমেন্ট সংরক্ষণের আগে একজন শিক্ষার্থী নির্বাচন করুন।'; message.classList.remove('hidden'); window.scrollTo({top:0,behavior:'smooth'}); }
    });
})();
</script>
@endpush
