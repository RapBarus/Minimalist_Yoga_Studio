<?php

namespace App\Http\Controllers\Coach;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CoachDashboardController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id');

        $coach = DB::table('coaches')->where('user_id', $userId)->first();

        if (!$coach) {
            return redirect()->route('login');
        }

        $coachId = $coach->coach_id;

        $totalSchedules = DB::table('schedules')
            ->where('coach_id', $coachId)
            ->where('status', 'upcoming')
            ->count();

        $completedClasses = DB::table('schedules')
            ->where('coach_id', $coachId)
            ->where('status', 'completed')
            ->count();

        $totalBookings = DB::table('bookings')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.schedule_id')
            ->where('schedules.coach_id', $coachId)
            ->count();

        $totalEarnings = $completedClasses * $coach->rate_per_class;

        $schedules = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->where('schedules.coach_id', $coachId)
            ->where('schedules.status', 'upcoming')
            ->where('schedules.schedule_date', '>=', now()->toDateString())
            ->orderBy('schedules.schedule_date', 'asc')
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.available_slots',
                'schedules.capacity',
                'classes.class_name'
            )
            ->get();

        return view('coach.coach_dashboard', [
            'totalSchedules' => $totalSchedules,
            'totalBookings' => $totalBookings,
            'completedClasses' => $completedClasses,
            'totalEarnings' => $totalEarnings,
            'schedules' => $schedules,
        ]);
    }
}
