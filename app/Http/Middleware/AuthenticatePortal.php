<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Symfony\Component\HttpFoundation\Response;
class AuthenticatePortal { public function handle(Request $request,Closure $next):Response { $user=$request->session()->get('portal_user'); if(!$user)return redirect()->route('login'); if(($user['role']??'admin')==='student' && !$request->routeIs('student.*','students.photo','logout')) return redirect()->route('student.portal'); if(($user['role']??'admin')!=='student' && $request->routeIs('student.*')) abort(403); return $next($request); } }
