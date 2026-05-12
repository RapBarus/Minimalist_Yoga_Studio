<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $schedules = DB::table('vw_available_schedules')
            ->where('schedule_date', '>=', now()->toDateString())
            ->orderBy('schedule_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        $classes = DB::table('classes')->orderBy('class_name')->get();

        $coaches = DB::table('coaches')
            ->join('users', 'coaches.user_id', '=', 'users.user_id')
            ->join('classes', 'coaches.class_id', '=', 'classes.class_id') // ← add this
            ->where('users.status', 'active')
            ->select('coaches.coach_id', 'users.name', 'classes.class_name') // ← not specialization
            ->get();

        $scheduleDates = DB::table('schedules')
            ->where('status', 'upcoming')
            ->pluck('schedule_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        return view('admin.dashboard', compact('schedules', 'classes', 'coaches', 'scheduleDates'));
    }
}
