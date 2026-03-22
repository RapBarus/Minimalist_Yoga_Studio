<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = DB::table('users')->where('role', 'customer')->count();
        $totalSchedules = DB::table('schedules')->where('status', 'upcoming')->count();
        $totalBookings = DB::table('bookings')->count();
        $totalClasses = DB::table('classes')->count();

        $recentBookings = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.user_id')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->orderBy('bookings.created_at', 'desc')
            ->limit(10)
            ->select(
                'bookings.booking_id',
                'bookings.status',
                'bookings.created_at',
                'users.name as user_name',
                'classes.class_name',
                'schedules.schedule_date',
                'schedules.start_time'
            )
            ->get();

        $upcomingSchedules = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('schedules.status', 'upcoming')
            ->where('schedules.schedule_date', '>=', now()->toDateString())
            ->orderBy('schedules.schedule_date', 'asc')
            ->limit(5)
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.available_slots',
                'schedules.capacity',
                'classes.class_name',
                'users.name as coach_name'
            )
            ->get();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalSchedules' => $totalSchedules,
            'totalBookings' => $totalBookings,
            'totalClasses' => $totalClasses,
            'recentBookings' => $recentBookings,
            'upcomingSchedules' => $upcomingSchedules,
            'admin_name' => Session::get('user_name'),
        ]);
    }
}
