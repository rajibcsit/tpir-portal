<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePortalRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->session()->get('portal_user');
        if (!$user) return redirect()->route('login');
        if (!in_array($user['role'] ?? 'admin', $roles, true)) abort(403, 'আপনার এই মডিউল ব্যবহারের অনুমতি নেই।');
        return $next($request);
    }
}
