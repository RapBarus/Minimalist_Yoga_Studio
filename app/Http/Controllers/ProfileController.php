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

        // If the password input is explicitly submitted as empty, show a field error.
        if ($request->has('password') && trim((string) $request->input('password')) === '') {
            return back()
                ->withErrors(['password' => 'Password tidak diubah karena input kosong.'])
                ->withInput();
        }

        // Validation
        $request->validate([
            'name' => 'nullable|string|max:100',
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
            'phone_number.regex' => 'Format nomor HP tidak valid. Contoh: +628123456789 atau 08123456789',
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
            $phone = $request->phone_number;
            $phone = str_replace(' ', '', $phone);

            if (str_starts_with($phone, '+0')) {
                $phone = '+62' . substr($phone, 2);
            } elseif (str_starts_with($phone, '+62')) {
            } elseif (str_starts_with($phone, '+')) {
                $phone = '+62' . substr($phone, 1);
            } elseif (str_starts_with($phone, '0')) {
                $phone = '+62' . substr($phone, 1);
            } elseif (str_starts_with($phone, '62')) {
                $phone = '+' . $phone;
            } else {
                $phone = '+62' . $phone;
            }

            $data['phone_number'] = $phone;
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
