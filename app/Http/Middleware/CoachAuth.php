<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CoachAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }

        if (Session::get('user_role') !== 'coach') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk coach.');
        }

        return $next($request);
    }
}
