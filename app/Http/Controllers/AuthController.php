<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9_]+$/'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{8,13}$/'],
            'password' => ['required', 'string', 'min:6', 'max:50', 'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'],
        ]);

        // FIX: Check against 'username'
        $exists = DB::table('users')->where('username', $request->username)->exists();
        if ($exists) {
            return back()->withErrors(['username' => 'Username sudah digunakan, coba yang lain.'])->withInput();
        }

        DB::table('users')->insert([
            'username' => $request->username,
            'name' => $request->name,
            'phone_number' => '+62' . ltrim($request->phone, '0'),
            'email' => null,
            'password_hash' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $key = 'login.' . str_replace(' ', '_', strtolower($request->username)) . '.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return back()->withErrors(['username' => "Terlalu banyak percobaan login. Coba lagi nanti."])->withInput();
        }

        $request->validate([
            'username' => ['required', 'string', 'regex:/^([a-zA-Z0-9_]+|[a-zA-Z0-9_]+@(admin|coach)\.com)$/'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $input = $request->username;
        $isEmail = str_contains($input, '@');

        if ($isEmail) {
            if (str_ends_with($input, '@admin.com')) {
                $name = str_replace('@admin.com', '', $input);
                $user = DB::table('users')->where('username', $name)->where('role', 'admin')->first();
            } elseif (str_ends_with($input, '@coach.com')) {
                $name = str_replace('@coach.com', '', $input);
                $user = DB::table('users')->where('username', $name)->where('role', 'coach')->first();
            } else {
                return back()->withErrors(['username' => 'Format tidak valid.'])->withInput();
            }
        } else {
            $user = DB::table('users')->where('username', $input)->where('role', 'customer')->first();
        }

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            RateLimiter::hit($key, 600);
            return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
        }

        if ($user->status === 'inactive') return back()->withErrors(['username' => 'Akun nonaktif.']);

        Session::put('user_id', $user->user_id);
        Session::put('user_name', $user->name);
        Session::put('user_role', $user->role);

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'coach' => redirect()->route('coach.dashboard'),
            default => redirect()->route('home')->with('success', 'Selamat datang, ' . $user->name . '!'),
        };
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('welcome');
    }
}
