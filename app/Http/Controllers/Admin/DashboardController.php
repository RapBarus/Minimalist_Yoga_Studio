<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $schedules = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->whereIn('schedules.status', ['upcoming', 'ongoing', 'completed'])
            ->where('schedules.schedule_date', '>=', now()->toDateString())
            ->where('schedules.schedule_date', '<=', now()->endOfMonth()->toDateString())
            ->orderBy('schedules.schedule_date', 'asc')
            ->orderBy('schedules.start_time', 'asc')
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.status',
                'schedules.capacity',
                'schedules.available_slots',
                'classes.class_name',
                'schedules.title',
                'users.name as coach_name',
                'coaches.coach_id',
                'coaches.rate_per_class',
                'coaches.profile_photo'
            )
            ->get();

        $classes = DB::table('classes')->orderBy('class_name')->get();

        $coaches = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->join('classes', 'coaches.class_id', '=', 'classes.class_id')
            ->where('users.status', 'active')
            ->select('coaches.coach_id', 'users.name', 'classes.class_name')
            ->get();

        $scheduleDates = DB::table('schedules')
            ->where('status', 'upcoming')
            ->where('schedule_date', '>=', now()->toDateString())
            ->where('schedule_date', '<=', now()->endOfMonth()->toDateString())
            ->pluck('schedule_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        $packages = DB::table('membership_packages')
            ->leftJoin('classes', 'membership_packages.class_id', '=', 'classes.class_id')
            ->orderBy('membership_packages.created_at', 'desc')
            ->select('membership_packages.*', 'classes.class_name')
            ->get();

        return view('admin.dashboard', compact('schedules', 'classes', 'coaches', 'scheduleDates', 'packages'));
    }
}
