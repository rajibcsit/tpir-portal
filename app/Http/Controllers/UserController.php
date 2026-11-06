<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(){ $users=User::whereIn('role',['teacher','accountant'])->orderBy('role')->orderBy('name')->paginate(20); $teacherCount=User::where('role','teacher')->count(); $accountantCount=User::where('role','accountant')->count(); return view('users.index',compact('users','teacherCount','accountantCount')); }
    public function create(){ $user=new User(); return view('users.create',compact('user')); }
    public function store(Request $request){
        $data=$request->validate(['name'=>'required|string|max:150','email'=>'required|email|max:150|unique:users,email','role'=>'required|in:teacher,accountant','password'=>'required|string|min:8|confirmed']);
        $data['password']=Hash::make($data['password']); User::create($data);
        return redirect()->route('users.index')->with('success','নতুন '.$data['role'].' account তৈরি হয়েছে।');
    }
    public function edit(User $user){ abort_unless(in_array($user->role,['teacher','accountant'],true),404); return view('users.create',compact('user')); }
    public function update(Request $request,User $user){
        abort_unless(in_array($user->role,['teacher','accountant'],true),404);
        $data=$request->validate(['name'=>'required|string|max:150','email'=>['required','email','max:150',Rule::unique('users','email')->ignore($user->id)],'role'=>'required|in:teacher,accountant','password'=>'nullable|string|min:8|confirmed']);
        if(empty($data['password'])) unset($data['password']); else $data['password']=Hash::make($data['password']);
        $user->update($data); return redirect()->route('users.index')->with('success','User account আপডেট হয়েছে।');
    }
    public function destroy(User $user){ abort_unless(in_array($user->role,['teacher','accountant'],true),404); if((int)$user->id===(int)session('portal_user.id')) return back()->withErrors(['user'=>'নিজের লগইন Account মুছে ফেলা যাবে না।']); $user->delete(); return back()->with('success','User account মুছে ফেলা হয়েছে।'); }
}
