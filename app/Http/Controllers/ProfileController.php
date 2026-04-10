<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id');

        $user = DB::table('users')->where('user_id', $userId)->first();

        if (!$user) {
            return redirect()->route('login');
        }

        $totalBookings = DB::table('bookings')->where('user_id', $userId)->count();
        $attendedBookings = DB::table('bookings')->where('user_id', $userId)->where('status', 'attended')->count();
        $upcomingBookings = DB::table('bookings')->where('user_id', $userId)->where('status', 'confirmed')->count();

        return view('pages.profile', [
            'user' => $user,
            'totalBookings' => $totalBookings,
            'attendedBookings' => $attendedBookings,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }
    public function update(Request $request)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        // Validation
        $request->validate([
            'name' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9_]+$/',
            'phone_number' => 'nullable|string|regex:/^\+?[0-9]{8,15}$/',
            'password' => [
                'nullable',
                'string',
                'min:6',
                'max:50',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
            ],
        ], [
            'name.regex' => 'Username hanya boleh huruf, angka, dan underscore.',
            'phone_number.regex' => 'Nomor HP hanya boleh angka, 8–15 digit.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.max' => 'Password maksimal 50 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf dan 1 angka.',
        ]);
        $data = [];

        if ($request->filled('name')) {
            $data['name'] = $request->name;
        }

        if ($request->filled('phone_number')) {
            $data['phone_number'] = $request->phone_number;
        }

        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }

        if (!empty($data)) {
            $data['updated_at'] = now();
            DB::table('users')->where('user_id', $userId)->update($data);

            if (isset($data['name'])) {
                Session::put('user_name', $data['name']);
            }
        }

        return redirect()
            ->route('profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
