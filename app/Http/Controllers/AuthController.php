<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(){ return view('auth.login'); }
    public function login(Request $request){
        $data=$request->validate(['email'=>'required|email','password'=>'required|string']);
        $user=User::where('email',$data['email'])->first();
        if(!$user || !Hash::check($data['password'],$user->password)) return back()->withErrors(['email'=>'ইমেইল অথবা পাসওয়ার্ড সঠিক নয়।'])->onlyInput('email');
        $request->session()->regenerate();
        $role=$user->role ?: 'admin';
        $request->session()->put('portal_user',['id'=>$user->id,'name'=>$user->name,'email'=>$user->email,'role'=>$role,'student_id'=>$user->student_id]);
        return match($role){'student'=>redirect()->route('student.portal'),'teacher'=>redirect()->route('attendance.index'),'accountant'=>redirect()->route('payments.index'),default=>redirect()->route('dashboard')};
    }
    public function logout(Request $request){$request->session()->forget('portal_user');$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('login');}
}
