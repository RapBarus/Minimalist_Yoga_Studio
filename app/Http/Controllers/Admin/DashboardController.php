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
        $schedules = DB::table('schedules')
            ->join('classes', 'schedules.class_id', '=', 'classes.class_id')
            ->join('coaches', 'schedules.coach_id', '=', 'coaches.coach_id')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('schedules.status', 'upcoming')
                        ->where('schedules.schedule_date', '>=', now()->toDateString());
                })->orWhere('schedules.status', 'completed');
            })
            ->orWhere('schedules.status', 'completed')
            ->orderBy('schedules.schedule_date', 'asc')
            ->orderBy('schedules.start_time', 'asc')
            ->select(
                'schedules.schedule_id',
                'schedules.schedule_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.capacity',
                'schedules.available_slots',
                'schedules.status',
                'classes.class_name',
                'coaches.rate_per_class',
                'users.name as coach_name'
            )
            ->get();

        $classes = DB::table('classes')->orderBy('class_name')->get();

        $coaches = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->where('users.status', 'active')
            ->select('coaches.coach_id', 'users.name', 'coaches.specialization')
            ->get();

        // Dates with schedules for calendar
        $scheduleDates = DB::table('schedules')
            ->where('status', 'upcoming')
            ->pluck('schedule_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        return view('admin.dashboard', [
            'schedules' => $schedules,
            'classes' => $classes,
            'coaches' => $coaches,
            'scheduleDates' => $scheduleDates,
        ]);
    }
}
