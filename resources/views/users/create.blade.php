@extends('layouts.app')
@section('title',$user->exists?'Edit Staff':'Create Staff')
@section('page-heading','Staff Account Management')
@section('content')
<div class="max-w-3xl mx-auto"><div class="mb-6"><h1 class="text-2xl font-bold">{{$user->exists?'Edit Staff Account':'Create Teacher / Accountant Login'}}</h1><p class="text-sm text-slate-500">প্রতিটি Staff-এর জন্য আলাদা Email ও Password দিন। Role অনুযায়ী Module Access স্বয়ংক্রিয়ভাবে নির্ধারিত হবে।</p></div>
<form method="POST" action="{{$user->exists?route('users.update',$user):route('users.store')}}" class="panel p-6 space-y-5">@csrf @if($user->exists)@method('PUT')@endif
<div><label class="block text-sm font-semibold mb-1">Full Name *</label><input class="field" name="name" value="{{old('name',$user->name)}}" required></div>
<div><label class="block text-sm font-semibold mb-1">Email Address *</label><input class="field" type="email" name="email" value="{{old('email',$user->email)}}" required></div>
<div><label class="block text-sm font-semibold mb-1">User Role *</label><select class="field" name="role" required><option value="teacher" @selected(old('role',$user->role)==='teacher')>Teacher</option><option value="accountant" @selected(old('role',$user->role)==='accountant')>Accountant</option></select></div>
<div class="grid md:grid-cols-2 gap-4"><div><label class="block text-sm font-semibold mb-1">{{$user->exists?'New Password (optional)':'Password *'}}</label><input class="field" type="password" name="password" {{$user->exists?'':'required'}} minlength="8" autocomplete="new-password"></div><div><label class="block text-sm font-semibold mb-1">Confirm Password {{$user->exists?'(if changing)':'*'}}</label><input class="field" type="password" name="password_confirmation" {{$user->exists?'':'required'}} minlength="8" autocomplete="new-password"></div></div>
<div class="rounded-xl bg-slate-50 p-4 text-sm"><b>Role permissions</b><ul class="list-disc pl-5 mt-2 space-y-1"><li><b>Teacher:</b> Daily Attendance, Attendance Reports, Exam Marks, Results & GPA; student directory read-only.</li><li><b>Accountant:</b> Fee & Payment Entry, Payment Reports, Receipts; student directory read-only.</li><li><b>Admin:</b> Can create and manage these staff accounts and has full system access.</li></ul></div>
<div class="flex gap-2"><button class="btn btn-primary">{{$user->exists?'Update Account':'Create Account'}}</button><a href="{{route('users.index')}}" class="btn border bg-white">Cancel</a></div></form></div>
@endsection
