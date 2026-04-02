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
            'name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
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
            DB::table('users')
                ->where('user_id', $userId)
                ->update($data);
        }

        return redirect()
            ->route('profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
