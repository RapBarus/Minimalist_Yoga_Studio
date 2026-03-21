<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Show Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Register
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ], [
            'username.required' => 'Username wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $exists = DB::table('users')->where('name', $request->username)->exists();
        if ($exists) {
            return back()
                ->withErrors(['username' => 'Username sudah digunakan, coba yang lain.'])
                ->withInput();
        }

        // Insert new user
        DB::table('users')->insert([
            'name' => $request->username,
            'phone_number' => '+62' . ltrim($request->phone, '0'),
            'email' => null,
            'password_hash' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    // Show Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $input = $request->username;
        $isEmail = str_contains($input, '@');
        // Admin login via username@admin.com format
        if ($isEmail) {
            if (!str_ends_with($input, '@admin.com')) {
                return back()
                    ->withErrors(['username' => 'Format tidak valid. Gunakan username@admin.com untuk admin.'])
                    ->withInput();
            }

            // Extract the name part before @admin.com
            $adminName = str_replace('@admin.com', '', $input);

            $user = DB::table('users')
                ->where('name', $adminName)
                ->where('role', 'admin')
                ->first();
        } else {
            // Customer/coach login via username
            $user = DB::table('users')
                ->where('name', $input)
                ->whereIn('role', ['customer', 'coach'])
                ->first();
        }

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()
                ->withErrors(['username' => 'Username/email atau password salah.'])
                ->withInput();
        }

        if ($user->status === 'inactive') {
            return back()
                ->withErrors(['username' => 'Akun Anda nonaktif. Hubungi admin.']);
        }

        // Save to session
        Session::put('user_id', $user->user_id);
        Session::put('user_name', $user->name);
        Session::put('user_role', $user->role);

        // Redirect based on role
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('home')->with('success', 'Selamat datang, ' . $user->name . '!'),
        };
    }

    // Logout
    public function logout()
    {
        Session::flush();
        return redirect()->route('welcome');
    }
}
