<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /** Shared academic filters used by the payment screens. */
    private function filterOptions(): array
    {
        return [
            'technologies' => Student::technologyOptions(),
            'semesters' => Student::query()->whereNotNull('semester')->distinct()->orderBy('semester')->pluck('semester'),
            'sessions' => Student::query()->whereNotNull('session')->distinct()->orderBy('session')->pluck('session'),
        ];
    }

    /** Build a scalable student query. Never load the full student table. */
    private function filteredStudents(Request $request)
    {
        $query = Student::query();

        foreach (['technology', 'semester', 'session', 'shift', 'status'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        if ($request->filled('search')) {
            $term = trim((string) $request->input('search'));
            $query->where(function ($sub) use ($term) {
                $sub->where('student_id', 'like', "%{$term}%")
                    ->orWhere('name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = Payment::query()->with('student')->latest();

        if ($request->input('filter') === 'due') {
            $query->whereColumn('amount_paid', '<', 'amount_due');
        }
        if ($request->filled('search')) {
            $term = trim((string) $request->input('search'));
            $query->whereHas('student', function ($studentQuery) use ($term) {
                $studentQuery->where('student_id', 'like', "%{$term}%")
                    ->orWhere('name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%");
            });
        }
        foreach (['technology', 'semester', 'session'] as $field) {
            if ($request->filled($field)) {
                $value = $request->input($field);
                $query->whereHas('student', fn ($studentQuery) => $studentQuery->where($field, $value));
            }
        }

        $payments = $query->paginate(20)->withQueryString();
        $statsQuery = Payment::query();
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'collected' => (clone $statsQuery)->sum('amount_paid'),
            'due' => max(0, (float) (clone $statsQuery)->sum('amount_due') - (float) (clone $statsQuery)->sum('amount_paid')),
        ];

        return view('payments.index', array_merge(compact('payments', 'stats'), $this->filterOptions()));
    }

    public function print(Request $request)
    {
        $query = Payment::with('student')->latest();
        if ($request->input('filter') === 'due') $query->whereColumn('amount_paid', '<', 'amount_due');
        if ($request->filled('search')) { $term=trim((string)$request->input('search')); $query->whereHas('student', fn($q) => $q->where('student_id','like',"%{$term}%")->orWhere('name','like',"%{$term}%")->orWhere('phone','like',"%{$term}%")); }
        foreach (['technology','semester','session'] as $field) if ($request->filled($field)) $query->whereHas('student', fn($q) => $q->where($field,$request->input($field)));
        $payments=$query->limit(2000)->get(); return view('payments.print',compact('payments'));
    }

    public function receipt(Payment $payment)
    {
        $payment->load('student'); return view('payments.receipt',compact('payment'));
    }

    public function create(Request $request)
    {
        $payment = new Payment();
        $selectedStudentId = $request->input('student') ?? session()->getOldInput('student_id');
        $selectedStudent = $selectedStudentId ? Student::find($selectedStudentId) : null;
        return view('payments.create', array_merge(compact('payment', 'selectedStudent'), $this->filterOptions()));
    }

    /** AJAX lookup: returns at most 30 matching students for fast payment entry. */
    public function studentSearch(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'technology' => ['nullable','string',\Illuminate\Validation\Rule::in(Student::technologyOptions())],
            'semester' => 'nullable|string|max:30',
            'session' => 'nullable|string|max:30',
        ]);

        $studentQuery = $this->filteredStudents($request)->where('status', 'Active');
        $total = (clone $studentQuery)->count();
        $students = $studentQuery->orderBy('student_id')->limit(30)->get(['id', 'student_id', 'name', 'technology', 'semester', 'session', 'phone']);

        return response()->json([
            'data' => $students,
            'count' => $students->count(),
            'total' => $total,
            'limit' => 30,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:150',
            'amount_due' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0|lte:amount_due',
            'due_date' => 'nullable|date',
            'paid_date' => 'nullable|date',
            'method' => 'nullable|string|max:50',
            'note' => 'nullable|string',
        ]);
        Payment::create($data);
        return redirect()->route('payments.index')->with('success', 'শিক্ষার্থীর পেমেন্ট সফলভাবে সংরক্ষণ হয়েছে।');
    }

    public function edit(Payment $payment)
    {
        $selectedStudent = $payment->student;
        return view('payments.create', array_merge(compact('payment', 'selectedStudent'), $this->filterOptions()));
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:150',
            'amount_due' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0|lte:amount_due',
            'due_date' => 'nullable|date',
            'paid_date' => 'nullable|date',
            'method' => 'nullable|string|max:50',
            'note' => 'nullable|string',
        ]);
        $payment->update($data);
        return redirect()->route('payments.index')->with('success', 'পেমেন্ট আপডেট হয়েছে।');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'পেমেন্ট রেকর্ড মুছে ফেলা হয়েছে।');
    }
}
