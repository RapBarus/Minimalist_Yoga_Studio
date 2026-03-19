<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
}
