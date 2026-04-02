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
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]{8,13}$/',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:50',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.regex' => 'Username hanya boleh huruf, angka, dan underscore. Tanpa spasi.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.regex' => 'Nomor HP hanya boleh angka, 8–13 digit (tanpa awalan 0).',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.max' => 'Password maksimal 50 karakter.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf dan 1 angka.',
        ]);

        $exists = DB::table('users')->where('name', $request->username)->exists();
        if ($exists) {
            return back()
                ->withErrors(['username' => 'Username sudah digunakan, coba yang lain.'])
                ->withInput();
        }

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
            'username' => [
                'required',
                'string',
                'regex:/^([a-zA-Z0-9_]+|[a-zA-Z0-9_]+@(admin|coach)\.com)$/',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.regex' => 'Format tidak valid. Gunakan username, username@admin.com, atau username@coach.com.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $input = $request->username;
        $isEmail = str_contains($input, '@');

        if ($isEmail) {
            if (str_ends_with($input, '@admin.com')) {
                $name = str_replace('@admin.com', '', $input);
                $user = DB::table('users')
                    ->where('name', $name)
                    ->where('role', 'admin')
                    ->first();

            } elseif (str_ends_with($input, '@coach.com')) {
                $name = str_replace('@coach.com', '', $input);
                $user = DB::table('users')
                    ->where('name', $name)
                    ->where('role', 'coach')
                    ->first();

            } else {
                return back()
                    ->withErrors(['username' => 'Format tidak valid. Gunakan username@admin.com atau username@coach.com.'])
                    ->withInput();
            }
        } else {
            $user = DB::table('users')
                ->where('name', $input)
                ->where('role', 'customer')
                ->first();
        }

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->withInput();
        }

        if ($user->status === 'inactive') {
            return back()
                ->withErrors(['username' => 'Akun Anda nonaktif. Hubungi admin.']);
        }

        Session::put('user_id', $user->user_id);
        Session::put('user_name', $user->name);
        Session::put('user_role', $user->role);

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'coach' => redirect()->route('coach.dashboard'),
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
